<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedCategory = '';
    public $showModal = false;
    public $productId;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $image;
    public $existingImageUrl;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:1024',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount(Request $request)
    {
        if ($request->has('product')) {
            $this->search = $request->product;
        }
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.products-list', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->reset(['productId', 'name', 'description', 'price', 'category_id']);
        $this->dispatch('open-modal', 'product-form');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->existingImageUrl = $product->image_url;

        $this->dispatch('open-modal', 'product-form');
    }

    #[On('save')] 
    public function save()
    {
        $this->validate();

        $imageUrl = null;
        if ($this->image) {
            $imageCloud = Cloudinary::upload($this->image->getRealPath(), ['folder' => env('CLOUDINARY_FOLDER') . '/products']);
            $imageUrl = $imageCloud->getSecurePath();
        }

        if ($this->productId) {
            $product = Product::find($this->productId);
            $product->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'image_url' => $imageUrl ?? $product->image_url,
            ]);
        } else {
            Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'image_url' => $imageUrl,
            ]);
        }

        $this->dispatch('close-modal', 'product-form');
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Producto guardado correctamente']);
        $this->reset(['productId', 'name', 'description', 'price', 'category_id', 'image']);
    }

    #[On('delete-product')] 
    public function delete($productId)
    {
        Product::destroy($productId);
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Producto eliminado correctamente']);
    }

    public function deleteImage()
    {
        if ($this->productId) {
            $product = Product::find($this->productId);
            $product->update(['image_url' => null]);
            $this->existingImageUrl = null;
            $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Imagen eliminada correctamente']);
        }
    }
}
