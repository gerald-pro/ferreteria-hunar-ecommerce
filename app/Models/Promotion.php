<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Promotion extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'discount_percentage', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isActive()
    {
        $today = now();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('promotions.show', ['promotion' => $this->id]);

        return new SearchResult(
            $this,
            "Promoción:  {$this->name} (Descuento: $this->discount_percentage)",
            $url
        );
    }
}
