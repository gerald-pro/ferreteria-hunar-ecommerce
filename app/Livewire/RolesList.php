<?php

namespace App\Livewire;

use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesList extends Component
{
    public $name;
    public $roleId;
    public $permissions = [];
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'permissions' => 'array',
    ];

    public function create()
    {
        $this->reset(['roleId', 'permissions', 'isEditing', 'name']);
        $this->dispatch('open-modal', 'role-form');
    }

    public function save()
    {
        $this->validate();

        if ($this->roleId) {
            $this->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->roleId)],
                'permissions' => 'array',
            ]);

            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->permissions);
            $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Rol actualizado con éxito.']);
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->permissions);
            $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Rol creado con éxito.']);
        }

        $this->dispatch('close-modal', 'role-form');
        $this->resetInput();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->dispatch('open-modal', 'role-form');
    }


    #[On('delete')]
    public function delete($roleId)
    {
        $role = Role::findOrFail($roleId);

        if ($role->users()->count() > 0) {
            $this->dispatch('toast:message', ['status' => 'error', 'message' => 'No se puede eliminar un rol asignado a usuarios.']);
            return;
        }

        $role->delete();
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Rol eliminado con éxito.']);
    }

    private function resetInput()
    {
        $this->name = '';
        $this->roleId = null;
        $this->permissions = [];
        $this->isEditing = false;
    }

    public function render()
    {
        $roles = Role::all()->sortBy('id');
        $allPermissions = Permission::all();

        return view('livewire.roles-list', [
            'roles' => $roles,
            'allPermissions' => $allPermissions,
        ]);
    }
}
