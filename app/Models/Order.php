<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Order extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'delivery_address',
        'payment_method',
        'billing_name',
        'billing_id',
        'delivery_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('admin.orders', ['order' => $this->id]);

        return new SearchResult(
            $this,
            "Pedido nro {$this->id} (Bs. $this->total_amount)",
            $url
        );
    }
}
