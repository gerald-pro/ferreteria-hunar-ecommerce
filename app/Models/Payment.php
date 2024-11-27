<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Payment extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'paid_amount',
        'status',
        'qr_image',
        'qr_expiration_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('admin.payments', ['payment' => $this->id]);

        return new SearchResult(
            $this,
            "Pago nro  {$this->id} (Bs. $this->paid_amount)",
            $url
        );
    }
}
