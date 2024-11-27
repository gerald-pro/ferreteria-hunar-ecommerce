<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Role;
use App\Models\SidebarItem;
use App\Models\User;
use Livewire\Component;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class GlobalSearcher extends Component
{
    public $query = "";
    public $results = [];

    public function render()
    {
        return view('livewire.global-searcher');
    }

    public function search()
    {
        if (!empty($this->query)) {
            if (!is_numeric($this->query)) {
                $searchResults = (new Search())
                    ->registerModel(Category::class, ['name'])
                    ->registerModel(Product::class, ['name', 'description'])
                    ->registerModel(User::class, ['name', 'email'])
                    ->registerModel(Role::class, ['name'])
                    ->registerModel(SidebarItem::class, ['name'])
                    ->search($this->query);
            } else {
                $searchResults = (new Search())
                    ->registerModel(Product::class, function (ModelSearchAspect $aspect) {
                        $aspect->addExactSearchableAttribute('price');
                    })
                    ->registerModel(Payment::class, function (ModelSearchAspect $aspect) {
                        $aspect->addExactSearchableAttribute('paid_amount');
                    })
                    ->registerModel(Order::class, function (ModelSearchAspect $aspect) {
                        $aspect->addExactSearchableAttribute('total_amount');
                    })
                    ->search($this->query);
            }

            $this->results = $searchResults->map(function ($result) {
                return [
                    'title' => $result->title,
                    'url' => $result->url,
                ];
            })->toArray();
        } else {
            $this->results = [];
        }
    }

    public function updatedQuery()
    {
        $this->search();
    }
}
