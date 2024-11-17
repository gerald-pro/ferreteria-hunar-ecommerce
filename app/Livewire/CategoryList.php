<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $categoryId;
    public $name;
    public $description;

    public $specificCategoryId;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
    ];

    public function mount(Request $request)
    {
        if ($request->has('category')) {
            $this->specificCategoryId = $request->get('category');
        }
    }


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

    public function render()
    {
        $categories = Category::query()
            ->when($this->specificCategoryId, function ($query) {
                $query->where('id', $this->specificCategoryId);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.category-list', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->reset(['categoryId', 'name', 'description']);
        $this->dispatch('open-modal', 'category-form');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->dispatch('open-modal', 'category-form');
    }

    #[On('save')]
    public function save()
    {
        $this->validate();

        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
        }

        $this->dispatch('close-modal', 'category-form');
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Categoría guardada correctamente']);
        $this->reset(['categoryId', 'name', 'description']);
    }

    #[On('delete-category')]
    public function delete($categoryId)
    {
        Category::destroy($categoryId);
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Categoría eliminada correctamente']);
    }
}
