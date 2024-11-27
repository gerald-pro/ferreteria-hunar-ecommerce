<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Role extends Model implements Searchable
{
    public function getSearchResult(): SearchResult
    {
        $url = route('roles.index');

        return new \Spatie\Searchable\SearchResult(
            $this,
            "Rol: {$this->name}",
            $url
        );
    }
}
