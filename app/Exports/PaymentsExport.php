<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentsExport implements FromCollection
{
    use Exportable;
    public $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $result = Payment::whereIn('payments.id', $this->payments)
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(['payments.id', 'users.name as user_name', 'payments.created_at', 'payments.paid_amount', 'payments.status'])
            ->get()
            ->map(function ($payment) {
                return [
                    'ID' => $payment->id,
                    'Usuario' => $payment->user_name,
                    'Fecha' => $payment->created_at->format('d/m/Y H:i'),
                    'Monto Total' => number_format($payment->paid_amount, 2) . ' Bs',
                    'Estado' => $payment->status,
                ];
            });

        return new Collection($result);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Usuario',
            'Fecha',
            'Monto Total',
            'Estado',
        ];
    }
}
