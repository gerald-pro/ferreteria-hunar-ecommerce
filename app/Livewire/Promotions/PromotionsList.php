<?php

namespace App\Livewire\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionsList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

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
        $promotions = Promotion::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.promotions.promotions-list', [
            'promotions' => $promotions,
        ]);
    }

    #[On('delete-promotion')]
    public function delete($promotionId)
    {
        Promotion::destroy($promotionId);
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Promoción eliminada correctamente']);
    }
}
