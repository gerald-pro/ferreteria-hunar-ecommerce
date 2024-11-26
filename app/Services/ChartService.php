<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChartService
{
    public function getSalesOverTime($timeRange)
    {
        $endDate = Carbon::now();
        $startDate = $this->getStartDate($timeRange);
        $groupBy = $this->getGroupByClause($timeRange);

        return Order::where('created_at', '>=', $startDate->toDateTimeString())
            ->where('created_at', '<=', $endDate->toDateTimeString())
            ->selectRaw("$groupBy as period, SUM(total_amount) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($sale) {
                return [
                    'x' => $sale->period,
                    'y' => floatval($sale->total)
                ];
            });
    }

    private function getGroupByClause($timeRange)
    {
        switch ($timeRange) {
            case 'month':
                return "TO_CHAR(created_at, 'YYYY-MM-DD')";
            case 'year':
                return "TO_CHAR(created_at, 'YYYY-MM')";
            case 'week':
            default:
                return "DATE(created_at)";
        }
    }

    public function getTopSellingProducts($limit = 5, $dateRange = 'all')
    {
        $query = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with(['product' => function ($query) {
                $query->withTrashed();
            }])
            ->groupBy('product_id');

        if ($dateRange !== 'all') {
            $startDate = $this->getStartDate($dateRange);
            $query->whereHas('order', function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            });
        }

        return $query->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->product->name,
                    'y' => $item->total_quantity
                ];
            });
    }

    public function getSalesByCategory($timeRange = 'all', $sortBy = 'sales', $limit = 10)
    {
        $query = Category::with(['products.orderItems' => function ($query) use ($timeRange) {
            if ($timeRange !== 'all') {
                $startDate = $this->getStartDate($timeRange);
                $query->whereHas('order', function ($q) use ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                });
            }
        }]);

        $categories = $query->get()->map(function ($category) {
            $totalSales = $category->products->sum(function ($product) {
                return $product->orderItems->sum(function ($orderItem) {
                    return $orderItem->quantity * $orderItem->price;
                });
            });

            return [
                'x' => $category->name,
                'y' => $totalSales
            ];
        });

        if ($sortBy === 'sales') {
            $categories = $categories->sortByDesc('y');
        } else {
            $categories = $categories->sortBy('x');
        }

        if ($limit !== 'all') {
            $categories = $categories->take($limit);
        }

        return $categories->values();
    }

    public function getPaymentMethodDistribution($timeRange = 'all', $sortBy = 'count')
    {
        $query = Order::select('payment_method', DB::raw('COUNT(*) as count'));

        if ($timeRange !== 'all') {
            $startDate = $this->getStartDate($timeRange);
            $query->where('created_at', '>=', $startDate);
        }

        $data = $query->groupBy('payment_method')
            ->get()
            ->map(function ($order) {
                return [
                    'x' => $order->payment_method,
                    'y' => $order->count
                ];
            });

        if ($sortBy === 'count') {
            $data = $data->sortByDesc('y');
        } else {
            $data = $data->sortBy('x');
        }

        return $data->values();
    }

    private function getStartDate($timeRange)
    {
        return match ($timeRange) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subWeek(),
        };
    }

    public function getProductsWithLowStock($limit = 5, $threshold = null)
    {
        $defaultStock = 20;
        $threshold = $threshold ?? ($defaultStock * 0.2);

        $query = Product::select('products.id', 'products.name', 'products.stock')
            ->orderBy('products.stock', 'asc')
            ->limit($limit);

        return $query->get()
            ->map(function ($product) {
                return [
                    'x' => $product->name,
                    'y' => $product->stock,
                ];
            });
    }
}
