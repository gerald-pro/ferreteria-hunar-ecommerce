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
            $order->delivery_status = 'COMPLETADO';
            $order->save();

            if ($order->payment_method === 'ELECTRONICO') {
                $payment = $order->payment;
                if ($payment && $payment->status !== 'PAGADO') {
                    $payment->status = 'PAGADO';
                    $payment->save();
                }
            } else if ($order->payment_method === 'CONTRA_ENTREGA') {
                if (!$order->payment) {
                    Payment::create([
                        'order_id' => $order->id,
                        'total_amount' => $order->total_amount,
                        'status' => 'PAGADO',
                        'transaction_id' => 'CONTRA_ENTREGA-' . $order->id,
                    ]);
                } else {
                    $order->payment->status = 'PAGADO';
                    $order->payment->save();
                }
            }

            return $order;
        });
    }

    public function cancelOrder(Order $order)
    {
        if ($order->delivery_status === 'COMPLETADO') {
            throw new \Exception('No se pueden cancelar pedidos completados.');
        }

        return DB::transaction(function () use ($order) {
            $order->delivery_status = 'CANCELADO';
            $order->save();

            $payment = $order->payment;
            if ($payment && $payment->status !== 'CANCELADO') {
                $payment->status = 'CANCELADO';
                $payment->save();
            }

            return $order;
        });
    }
}