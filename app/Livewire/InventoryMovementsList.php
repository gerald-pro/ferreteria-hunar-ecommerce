<?php

namespace App\Livewire;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryMovementsList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedProduct = '';
    public $selectedType = '';

    // Variables para el modal
    public $showModal = false;
    public $movementId;
    public $product_id;
    public $quantity;
    public $type;
    public $notes;

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'type' => 'required|in:IN,OUT',
        'notes' => 'nullable|string|max:255',
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

    public function render()
    {
        $movements = InventoryMovement::query()
            ->with('product')
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedProduct, function ($query) {
                $query->where('product_id', $this->selectedProduct);
            })
            ->when($this->selectedType, function ($query) {
                $query->where('type', $this->selectedType);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $products = Product::orderBy('name')->get();

        return view('livewire.inventory-movement-list', [
            'movements' => $movements,
            'products' => $products,
        ]);
    }

    public function create()
    {
        $this->reset(['movementId', 'product_id', 'quantity', 'type', 'notes']);
        $this->dispatch('open-modal', 'movement-form');
    }

    #[On('save')]
    public function save()
    {
        $this->validate();

        // Verificar stock disponible si es una salida
        if ($this->type === 'OUT') {
            $product = Product::find($this->product_id);
            if ($product->stock < $this->quantity) {
                $this->dispatch('toast:message', [
                    'status' => 'error',
                    'message' => 'No hay suficiente stock disponible'
                ]);
                return;
            }
        }

        try {
            DB::transaction(function () {
                // Crear el movimiento
                InventoryMovement::create([
                    'product_id' => $this->product_id,
                    'quantity' => $this->quantity,
                    'type' => $this->type,
                    'notes' => $this->notes,
                ]);

                // Actualizar el stock del producto
                $product = Product::find($this->product_id);
                $product->stock += $this->type === 'IN' ? $this->quantity : -$this->quantity;
                $product->save();
            });

            $this->dispatch('close-modal', 'movement-form');
            $this->dispatch('toast:message', [
                'status' => 'success',
                'message' => 'Movimiento registrado correctamente'
            ]);
            $this->reset(['movementId', 'product_id', 'quantity', 'type', 'notes']);
        } catch (\Exception $e) {
            $this->dispatch('toast:message', [
                'status' => 'error',
                'message' => 'Error al registrar el movimiento'
            ]);
        }
    }
}
