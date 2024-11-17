<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\SidebarGroup;
use App\Models\SidebarItem;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class SidebarItems extends Component
{
    public $items;
    public $showModal = false;
    public $editingItem = null;

    public $name, $page_id, $icon;
    public $sidebar_group_id;
    public $description;
    public int $status;
    public $permission;

    public Collection $sidebarGroups, $permissions, $pages;

    protected $validationAttributes = [
        'name' => 'nombre',
        'page_id' => 'ruta',
        'icon' => 'ícono',
        'sidebar_group_id' => 'grupo',
        'description' => 'descripción',
        'status' => 'estado'
    ];

    protected $rules = [
        'name' => 'required',
        'page_id' => 'required|exists:pages,id',
        'icon' => 'nullable|regex:/^[A-Za-z0-9\- ]+$/',
        'sidebar_group_id' => 'nullable|integer',
        'description' => 'nullable',
        'status' => 'numeric|min:0|max:1',
        'permission' => 'nullable',
    ];

    public function mount()
    {
        $this->resetForm();
        $this->loadItems();
    }


    public function loadItems()
    {
        $this->items = SidebarItem::all()->sortBy('id');
        $this->sidebarGroups = SidebarGroup::all();
        $this->permissions = Permission::all();
        $this->pages = Page::all();
    }

    public function render()
    {
        return view('livewire.sidebar-items');
    }

    public function create()
    {
        $this->showModal = true;
        $this->editingItem = null;
    }

    public function edit(SidebarItem $item)
    {
        $this->name = $item->name;
        $this->page_id = $item->page_id;
        $this->icon = $item->icon;
        $this->sidebar_group_id = $item->sidebar_group_id;
        $this->description = $item->description;
        $this->status = $item->status;
        $this->permission = $item->permission;
        $this->editingItem = $item->id;
        $this->showModal = true;
    }


    public function save()
    {
        $this->validate();

        if ($this->sidebar_group_id == "") {
            $this->sidebar_group_id = null;
        }

        $data = [
            'name' => $this->name,
            'icon' => $this->icon,
            'page_id' => $this->page_id,
            'sidebar_group_id' => $this->sidebar_group_id,
            'description' => $this->description,
            'status' => $this->status,
            'permission' => $this->permission
        ];

        if ($this->editingItem) {
            SidebarItem::find($this->editingItem)->update($data);
            $message = 'Item actualizado con éxito';
        } else {
            SidebarItem::create($data);
            $message = 'Item creado con éxito';
        }

        return redirect()->route('sidebar')->with('success', $message);
    }


    #[On('delete-item')]
    public function delete($id)
    {
        $item = SidebarItem::findOrFail($id);
        $item->delete();
        return redirect()->route('administration.sidebar')->with('success', 'Item eliminado con éxito');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->page_id = '';
        $this->icon = '';
        $this->sidebar_group_id = null;
        $this->description = '';
        $this->status = 1;
        $this->permission = '';
        $this->editingItem = null;
    }
}
