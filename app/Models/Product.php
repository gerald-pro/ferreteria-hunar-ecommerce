<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Product extends Model implements Searchable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'category_id',
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('products.show', ['product' => $this->id]);

        return new SearchResult(
            $this,
            "Producto:  {$this->name} (Bs. $this->price)",
            $url
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }


    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }

    public function getCurrentPromotionAttribute()
    {
        return $this->promotions()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_active', true)
            ->orderBy('discount_percentage', 'desc')
            ->first();
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->currentPromotion) {
            return $this->price * (1 - $this->currentPromotion->discount_percentage / 100);
        }
        return $this->price;
    }
}
