<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function completeOrder(Order $order)
    {
        if ($order->delivery_status !== 'PENDIENTE') {
            throw new \Exception('Solo se pueden completar pedidos pendientes.');
        }

        return DB::transaction(function () use ($order) {
            // Marcar el pedido como completado
            $order->delivery_status = 'COMPLETADO';
            $order->save();

            // Manejar los pagos según el método de pago
            /* if ($order->payment_method === 'ELECTRONICO') {
                $payments = $order->payments;
                if ($payments) {
                    foreach ($payments as $payment) {
                        if ($payment->status !== 'PAGADO') {
                            $payment->status = 'PAGADO';
                            $payment->save();
                        }
                    }
                }
            } elseif ($order->payment_method === 'CONTRA_ENTREGA') {
                if ($order->payments->isEmpty()) {
                    // Crear un pago si no hay ninguno
                    Payment::create([
                        'order_id' => $order->id,
                        'paid_amount' => $order->total_amount,
                        'status' => 'PAGADO',
                        'transaction_id' => 'CONTRA_ENTREGA-' . $order->id,
                    ]);
                } else {
                    // Marcar todos los pagos existentes como pagados
                    foreach ($order->payments as $payment) {
                        $payment->status = 'PAGADO';
                        $payment->save();
                    }
                }
            } */

            return $order;
        });
    }

    public function cancelOrder(Order $order)
    {
        if ($order->delivery_status === 'COMPLETADO') {
            throw new \Exception('No se pueden cancelar pedidos completados.');
        }

        return DB::transaction(function () use ($order) {
            // Marcar el pedido como cancelado
            $order->delivery_status = 'CANCELADO';
            $order->save();

            // Cancelar todos los pagos asociados al pedido
            $payments = $order->payments;
            if ($payments) {
                foreach ($payments as $payment) {
                    if ($payment->status !== 'CANCELADO') {
                        $payment->status = 'CANCELADO';
                        $payment->save();
                    }
                }
            }

            return $order;
        });
    }
}
