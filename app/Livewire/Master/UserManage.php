<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Branch;
use App\Models\UserBranche;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManage extends Component
{
    use WithPagination;

    // Form User
    public $name, $username, $password;
    public $branch_id;

    public $showUserModal = false;

    protected $rules = [
        'name'      => 'required|string|max:255',
        'username'  => 'required|string|max:255|unique:users,username',
        'password'  => 'required|min:6',
        'branch_id' => 'required|exists:branches,id',
    ];

    public function openUserModal()
    {
        $this->resetForm();
        $this->showUserModal = true;
    }

    public function saveUser()
    {
        $this->validate();

        DB::transaction(function () {
            $user = User::create([
                'name'     => $this->name,
                'username' => $this->username,
                'password' => Hash::make($this->password),
            ]);

            $user->assignRole('Karyawan');

            UserBranche::create([
                'user_id'     => $user->id,
                'branches_id'=> $this->branch_id,
                'role'        => 'Karyawan',
                'is_active'   => true,
            ]);
        });

        session()->flash('message', 'User berhasil ditambahkan');
        $this->showUserModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'username', 'password', 'branch_id']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.master.user-manage', [
            'userBranches' => UserBranche::with(['user', 'branch'])
                ->latest()
                ->paginate(10),
            'branches' => Branch::all(),
        ]);
    }
}
