<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsersList extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public Collection $roles;

    public User $user;
    public $role = '';
    public $banReason = '';

    public $userId;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function mount(Request $request)
    {
        $this->roles = Role::all();

        if ($request->has('user')) {
            $this->userId = $request->get('user');
        }
    }

    public function edit(User $user)
    {
        $this->user = $user;
        $this->role = $user->getRoleNames()[0] ?? null;
        $this->dispatch('open-modal', 'rol-form');
    }

    public function save()
    {
        $this->user->syncRoles([$this->role]);
        $this->dispatch('close-modal', 'rol-form');
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Rol actualizado con éxito.']);
    }

    public function banForm(User $user)
    {
        $this->reset(['user', 'banReason']);
        $this->user = $user;
        $this->dispatch('open-modal', 'ban-form');
    }

    public function ban()
    {
        $this->user->is_banned = true;
        $this->user->ban_reason = $this->banReason;
        $this->user->save();
        $this->dispatch('close-modal', 'ban-form');
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Usuario baneado']);
    }

    public function unban(User $user)
    {
        $user->is_banned = false;
        $user->ban_reason = null;
        $user->save();
        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Usuario desbaneado']);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->userId, function ($query) {
                $query->where('id', $this->userId);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users-list', [
            'users' => $users,
        ]);
    }
}
