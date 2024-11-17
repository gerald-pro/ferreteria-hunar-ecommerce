<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    use Exportable;
    public $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $result = Order::whereIn('orders.id', $this->orders)
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(['orders.id', 'users.name as user_name', 'orders.created_at', 'orders.total_amount'])
            ->get()
            ->map(function ($order) {
                $items = OrderItem::where('order_id', $order->id)
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->select(['products.name as product_name', 'order_items.quantity'])
                    ->get();

                $order->items = $items->map(function ($item) {
                    return $item->product_name . ' (' . $item->quantity . ')';
                })->join(', ');

                return $order;
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
            'Items',
        ];
    }
}
