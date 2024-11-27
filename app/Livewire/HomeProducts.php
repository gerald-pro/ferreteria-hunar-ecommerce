<?php

namespace App\Livewire;

use App\Facades\Cart;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class HomeProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $minPrice = 0;
    public $maxPrice = 1000;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::all();

        $items = Product::with('promotions')
            ->where('stock', '>', 0)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'ILIKE', '%' . $this->search . '%')
                        ->orWhere('description', 'ILIKE', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->minPrice, function ($query) {
                $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function ($query) {
                $query->where('price', '<=', $this->maxPrice);
            })
            ->paginate(9);

        foreach ($items as &$product) {
            if (!$product->image_url) {
                $product->image_url = 'https://www.creativefabrica.com/wp-content/uploads/2018/12/Tools-icon-by-rudezstudio-2-580x386.jpg';
            }
        }

        return view('livewire.home-products', [
            'products' => $items,
            'categories' => $categories,
        ]);
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        Cart::add($product->id, $product->name, $product->discountedPrice);

        $this->dispatch('toast:message', [
            'message' => 'Producto añadido al carrito de compras',
            'status' => 'success',
        ]);
    }
}
