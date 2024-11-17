<?php

namespace App\Livewire;

use App\Models\SidebarGroup;
use Livewire\Attributes\On;
use Livewire\Component;

class SidebarGroups extends Component
{
    public $groups;
    public $showModal = false;
    public $editingGroup = null;
    public string $name, $icon, $description;
    public int $status;

    protected $rules = [
        'name' => 'required',
        'icon' => 'required|regex:/^[A-Za-z0-9\- ]+$/',
        'description' => 'nullable',
        'status' => 'numeric|min:0|max:1',
    ];

    protected $validationAttributes = [
        'name' => 'nombre',
        'icon' => 'ícono',
        'description' => 'descripción',
        'status' => 'estado'
    ];

    public function mount()
    {
        $this->loadGroups();
    }

    public function loadGroups()
    {
        $this->groups = SidebarGroup::all();
    }

    public function render()
    {
        return view('livewire.sidebar-groups');
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(SidebarGroup $group)
    {
        $this->editingGroup = $group;
        $this->name = $group->name;
        $this->icon = $group->icon;
        $this->description = $group->description;
        $this->status = $group->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingGroup) {
            $this->editingGroup->update([
                'name' => $this->name,
                'icon' => $this->icon,
                'description' => $this->description,
                'status' => $this->status,
            ]);
        } else {
            SidebarGroup::create([
                'name' => $this->name,
                'icon' => $this->icon,
                'description' => $this->description,
                'status' => $this->status,
            ]);
        }

        return redirect()->route('sidebar')->with('success', 'Recurso guardado con éxito');
    }

    #[On('delete-group')]
    public function delete($id)
    {
        $group = SidebarGroup::findOrFail($id);
        $group->delete();
        return redirect()->route('administration.sidebar')->with('success', 'Grupo eliminado con éxito');
    }

    private function resetForm()
    {
        $this->editingGroup = null;
        $this->name = '';
        $this->icon = '';
        $this->description = '';
        $this->status = true;
        $this->showModal = false;
    }
}
