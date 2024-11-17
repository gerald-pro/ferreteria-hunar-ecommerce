<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
