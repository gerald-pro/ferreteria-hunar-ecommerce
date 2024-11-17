<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class SidebarItem extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'page_id',
        'description',
        'visits',
        'sidebar_group_id',
        'icon',
        'status',
        'created_at'
    ];

    public function sidebarGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SidebarGroup::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getSearchResult(): SearchResult
    {
        $url = url('sidebar');

        return new \Spatie\Searchable\SearchResult(
            $this,
            "Sidebar (Item: {$this->name})",
            $url
        );
    }
}
