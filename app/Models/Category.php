<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Category extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('categories.index', ['category' => $this->id]);

        return new SearchResult(
            $this,
            "Categoria de producto:  {$this->name}",
            $url
        );
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
