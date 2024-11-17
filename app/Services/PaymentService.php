<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public static function processPayment(int $orderId, float $amount): array
    {
        try {
            $order = Order::with('user', 'orderItems.product')->findOrFail($orderId);

            // Validar que el monto a pagar no exceda el saldo pendiente
            $totalPaid = $order->payments()
                ->where('status', 'PAGADO')
                ->sum('paid_amount');

            $remainingAmount = $order->total_amount - $totalPaid;

            if ($amount > $remainingAmount) {
                throw new \Exception('El monto a pagar excede el saldo pendiente.');
            }

            $details = self::generateOrderDetails($order);
            $saleId = 'grupo13sa-' . $order->id . '-' . random_int(1000, 9999);

            // Generar nueva transacción con PagoFácil
            $transaction = self::generateTransaction($order, $details, $amount);

            $imageCloud = Cloudinary::upload($transaction['qrImage'], [
                'folder' => env('CLOUDINARY_FOLDER') . '/qrs'
            ]);
            $imageUrl = $imageCloud->getSecurePath();

            // Crear nuevo registro de pago
            Payment::create([
                'transaction_id' => $transaction['nroTransaction'],
                'order_id' => $order->id,
                'paid_amount' => $amount,
                'status' => 'PENDIENTE',
                'qr_image' => $imageUrl,
                'qr_expiration_date' => $transaction['expirationDate'],
            ]);

            return [
                'status' => 'success',
                'message' => 'Transacción procesada correctamente.'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }


    public static function generateTransaction(Order $order, array $details, float $amount)
    {
        try {
            $saleId = 'grupo13sa-' . $order->id . random_int(1000, 9999);

            $lcComerceID = env('PAGOFACIL_COMMERCE_ID');
            $lnMoneda = 2;
            $lnTelefono = 70480741;
            $lcNombreUsuario = $order->user->name;
            $lnMontoClienteEmpresa = $amount;
            $lnCiNit = 14495734;
            $lcCorreo = 'geraldjoseavalosseveriche@gmail.com';
            $lcUrlCallBack = env('PAGOFACIL_URL_CALLBACK');
            $lcUrlReturn = env('PAGOFACIL_URL_CALLBACK');
            $laPedidoDetalle = $details;

            $loClient = new Client();
            $lcUrl = env("PAGOFACIL_API_URL") . "/generarqrv2";

            $laHeader = [
                'Accept' => 'application/json'
            ];

            $laBody = [
                "tcCommerceID" => $lcComerceID,
                "tnMoneda" => $lnMoneda,
                "tnTelefono" => $lnTelefono,
                'tcNombreUsuario' => $lcNombreUsuario,
                'tnCiNit' => $lnCiNit,
                'tcNroPago' => $saleId,
                "tnMontoClienteEmpresa" => $lnMontoClienteEmpresa,
                "tcCorreo" => $lcCorreo,
                'tcUrlCallBack' => $lcUrlCallBack,
                "tcUrlReturn" => $lcUrlReturn,
                'taPedidoDetalle' => $laPedidoDetalle
            ];

            $loResponse = $loClient->post($lcUrl, [
                'headers' => $laHeader,
                'json' => $laBody
            ]);

            $laResult = json_decode($loResponse->getBody()->getContents());

            if ($laResult->error == 0) {
                return self::parseTransactionResponse($laResult, $saleId);
            } else {
                throw new Exception('Error al generar la transaccion en pagofacil. ' . $laResult->error);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::error("Error de cliente: " . $responseBodyAsString);
            throw new Exception('Error al generar la transacción en pagofacil. Error de cliente: ' . $response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::error("Error de servidor: " . $responseBodyAsString);
            throw new Exception('Error al generar la transacción en pagofacil. Error de servidor: ' . $response->getStatusCode());
        } catch (\Exception $e) {
            Log::error("Error inesperado: " . $e->getMessage());
            throw new Exception('Error inesperado al generar la transacción en pagofacil: ' . $e->getMessage());
        }
    }


    public static function checkTransaction(int $lnTransaccion)
    {
        try {
            $loClientEstado = new Client();
            $lcUrlEstadoTransaccion = env("PAGOFACIL_API_URL") . "/consultartransaccion";

            $loEstadoTransaccion = $loClientEstado->post($lcUrlEstadoTransaccion, [
                'headers' => ['Accept' => 'application/json'],
                'json' => ["TransaccionDePago" => $lnTransaccion]
            ]);

            $laResultEstadoTransaccion = json_decode($loEstadoTransaccion->getBody()->getContents());
            if ($laResultEstadoTransaccion->error == 0) {
                $result = strtolower($laResultEstadoTransaccion->values->messageEstado);
                return ['status' => strpos($result, 'procesado') !== false ? 'procesado' : 'en cola', 'message' => $laResultEstadoTransaccion->message];
            } else {
                return ['status' => 'error', 'message' => $laResultEstadoTransaccion->message];
            }
        } catch (\Throwable $th) {
            return ['status' => 'error', 'message' => $th->getMessage()];
        }
    }

    public static function updatePendingTransactions(int $userId = null, bool $updateQr = false): void
    {
        $pendingPayments = Payment::where('status', 'PENDIENTE')
            ->when($userId, function ($query) use ($userId) {
                $query->whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })
            ->get();

        $now = Carbon::now();

        foreach ($pendingPayments as $payment) {
            $expirationDate = null;
            if ($payment->qr_expiration_date) {
                $expirationDate = Carbon::parse($payment->qr_expiration_date);
            }

            if (($expirationDate != null && ($now->greaterThan($expirationDate)) || empty($payment->qr_image)) && $payment->status != 'PAGADO') {
                $payment->delete();
            } else {
                $response = self::checkTransaction($payment->transaction_id);
                if ($response['status'] == 'procesado') {
                    $payment->update(['status' => 'PAGADO']);

                    // Verificar si el pedido está completamente pagado
                    $order = $payment->order;
                    $totalPaid = $order->payments()
                        ->where('status', 'PAGADO')
                        ->sum('paid_amount');

                    if ($totalPaid >= $order->total_amount) {
                        // Aquí podrías disparar un evento o actualizar el estado del pedido si es necesario
                        Log::info("Pedido {$order->id} completamente pagado");
                    }
                }
            }
        }
    }


    private static function parseTransactionResponse($laResult, $saleId)
    {
        $laValues = explode(";", $laResult->values);
        $qrData = json_decode($laValues[1]);
        $qrImage = "data:image/png;base64," . $qrData->qrImage;
        $expirationDate = Carbon::createFromFormat('Y-m-d H:i:s', $qrData->expirationDate);

        return [
            'status' => 'success',
            'saleId' => $saleId,
            'nroTransaction' => $laValues[0] ?? '',
            'error' => $laResult->error ?? 1,
            'message' => $laResult->message ?? '',
            'messageSistema' => $laResult->messageSistema ?? '',
            'qrImage' => $qrImage,
            'expirationDate' => $expirationDate
        ];
    }

    private static function generateOrderDetails(Order $order): array
    {
        return $order->orderItems->map(function ($item) {
            return [
                "Serial" => $item->product_id,
                "Producto" => $item->product->name,
                "Cantidad" => $item->quantity,
                "Precio" => $item->price,
                "Descuento" => "0",
                "Total" => $item->price * $item->quantity
            ];
        })->toArray();
    }

    public function callback(Request $request)
    {
        try {
            $orderId = $request->input("PedidoID");
            $status = $request->input("Estado");
            $transactionId = $request->input("TransaccionId");

            if ($status == 2) {
                // Buscar el pago específico por transaction_id
                $payment = Payment::where('transaction_id', $transactionId)->first();

                if ($payment && $payment->status != 'PAGADO') {
                    $payment->update(['status' => 'PAGADO']);

                    // Verificar si el pedido está completamente pagado
                    $order = $payment->order;
                    $totalPaid = $order->payments()
                        ->where('status', 'PAGADO')
                        ->sum('paid_amount');

                    if ($totalPaid >= $order->total_amount) {
                        // Aquí podrías disparar un evento o actualizar el estado del pedido si es necesario
                        Log::info("Pedido {$order->id} completamente pagado");
                    }
                }
            }

            return response()->json([
                'error' => 0,
                'status' => 1,
                'message' => "Pago realizado correctamente.",
                'values' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 1,
                'status' => 1,
                'messageSistema' => "[TRY/CATCH] " . $th->getMessage(),
                'message' => "No se pudo realizar el pago, por favor intente de nuevo.",
                'values' => false
            ]);
        }
    }
}
