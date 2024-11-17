<?php

namespace App\Livewire\Promotions;

use App\Models\Product;
use App\Models\Promotion;
use Livewire\Component;

class PromotionForm extends Component
{
    public $promotionId;
    public $name;
    public $description;
    public $discount_percentage;
    public $start_date;
    public $end_date;
    public $is_active = true;

    public $search = '';
    public $selectedProducts = [];

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
        'discount_percentage' => 'required|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_active' => 'boolean',
        'selectedProducts' => 'array',
    ];

    public function mount($promotionId = null)
    {
        if ($promotionId) {
            $promotion = Promotion::findOrFail($promotionId);
            $this->promotionId = $promotionId;
            $this->name = $promotion->name;
            $this->description = $promotion->description;
            $this->discount_percentage = $promotion->discount_percentage;
            $this->start_date = $promotion->start_date->format('Y-m-d H:s');
            $this->end_date = $promotion->end_date->format('Y-m-d H:s');
            $this->is_active = $promotion->is_active;
            $this->selectedProducts = $promotion->products->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->promotionId) {
            $promotion = Promotion::find($this->promotionId);
            $promotion->update([
                'name' => $this->name,
                'description' => $this->description,
                'discount_percentage' => $this->discount_percentage,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]);
        } else {
            $promotion = Promotion::create([
                'name' => $this->name,
                'description' => $this->description,
                'discount_percentage' => $this->discount_percentage,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]);
        }

        $promotion->products()->sync($this->selectedProducts);
        return redirect()->route('promotions.index');
    }

    public function render()
    {
        return view('livewire.promotions.promotion-form', [
            'products' => Product::all(),
        ]);
    }
}
