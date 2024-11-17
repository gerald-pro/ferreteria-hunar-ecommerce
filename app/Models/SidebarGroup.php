<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class SidebarGroup extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'status',
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SidebarItem::class);
    }
    
    public function getSearchResult(): SearchResult
    {
        $url = url('sidebar');

        return new \Spatie\Searchable\SearchResult(
            $this,
            "Sidebar (Grupo: {$this->name})",
            $url
        );
    }
}
