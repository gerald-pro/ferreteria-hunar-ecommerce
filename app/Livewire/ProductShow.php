<?php

namespace App\Livewire;

use App\Facades\Cart;
use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public $product;
    public $recommendedProducts;

    public function mount($id)
    {
        $this->product = Product::with('promotions')->findOrFail($id);
        $this->setDefaultImage($this->product);
        $this->loadRecommendedProducts();
    }

    public function loadRecommendedProducts()
    {
        $recomended = Product::with('promotions')
        ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->take(4)
            ->get();

        foreach ($recomended as $product) {
            $this->setDefaultImage($product);
        }


        $this->recommendedProducts = $recomended;
    }

    private function setDefaultImage($product)
    {
        if (!$product->image_url) {
            $product->image_url = 'https://www.creativefabrica.com/wp-content/uploads/2018/12/Tools-icon-by-rudezstudio-2-580x386.jpg';
        }
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        Cart::add($product->id, $product->name, $product->discountedPrice);

        $this->dispatch('toast:message', [
            'message' => 'Producto añadido al carrito de compras',
            'status' => 'success',
        ]);

        $this->setDefaultImage($this->product);
        foreach ($this->recommendedProducts as $product) {
            $this->setDefaultImage($product);
        }
    }

    public function render()
    {
        return view('livewire.product-show');
    }
}
