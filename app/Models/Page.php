<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Page extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'route',
        'visits',
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('pages.index');

        return new SearchResult(
            $this,
            "Página:  {$this->route} (visitas: $this->visits)",
            $url
        );
    }
}
