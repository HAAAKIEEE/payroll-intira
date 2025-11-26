<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManagement extends Component
{
     public $roles;
    public $permissions;
    public $selectedRoleId;
    public $rolePermissions = [];

    public function mount()
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function updatedSelectedRoleId($value)
    {
        $role = Role::find($value);
        $this->rolePermissions = $role ? $role->permissions->pluck('name')->toArray() : [];
    }

    public function togglePermission($permissionName)
    {
        $role = Role::find($this->selectedRoleId);

        if (!$role) return;

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        $this->rolePermissions = $role->permissions->pluck('name')->toArray();

        session()->flash('success', 'Permissions updated successfully.');
    }

    public function render()
    {
        return view('livewire.master.role-permission-management');
    }
}
