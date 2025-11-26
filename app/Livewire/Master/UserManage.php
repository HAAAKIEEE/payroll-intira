<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\UserBranche;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManage extends Component
{
    use WithPagination;
    public $quickAssignUserId;
    public $selectedEmployee;
    public $selectedBranch;
    public $selectedRole;

    public $employees = [];
    public $branches = [];
    public $roles = [];
    public $showQuickAssignModal = false;


    public $search = '';
    // Form properties untuk assign branch
    public $selected_user_id, $selected_branch_id, $selected_role;
    public $start_at, $end_at, $is_active = true;

    // Form properties untuk tambah user baru
    public $name, $username, $password;
    public $full_name, $employee_code, $position, $hire_date;
    public $grade, $address, $years_of_service = 0, $education;
    public $account_number, $npwp_number, $nik;
    public $branch_id, $role, $assignment_start_at;

    // Modal states
    public $showAssignModal = false;
    public $showUserModal = false;
    public $showUnassignedUsers = false;
    public $showUnassignedEmployees = false;
    public function openQuickAssign($userId)
    {
        $this->quickAssignUserId = $userId;
        $this->showQuickAssignModal = true;

        // Ambil data employee yang belum terhubung
        $this->employees = Employee::whereDoesntHave('user')->get();

        // Ambil branch & role
        $this->branches = Branch::all();
        $this->roles = Role::all();
    }

    public function saveQuickAssign()
    {
        $this->validate([
            'selectedEmployee' => 'required',
            'selectedBranch' => 'required',
            'selectedRole' => 'required',
        ]);

        // Update employee -> set user_id
        // Employee::where('id', $this->selectedEmployee)
        //     ->update([
        //         'user_id' => $this->quickAssignUserId
        //     ]);
            User::where('id', $this->quickAssignUserId)
    ->update([
        'employee_id' => $this->selectedEmployee
    ]);


        // Insert ke tabel user_branche
        UserBranche::create([
            'user_id' => $this->quickAssignUserId,
            'branches_id' => $this->selectedBranch,
            'role' => $this->selectedRole,
            'is_active' => true,
            'start_at' => now(),
        ]);

        $this->reset(['showQuickAssignModal']);
        session()->flash('message', 'User berhasil di-assign.');
    }


    protected function rules()
    {
        $rules = [
            'selected_user_id' => 'required|exists:users,id',
            'selected_branch_id' => 'required|exists:branches,id',
            'selected_role' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
        ];

        // Rules untuk tambah user baru
        if ($this->showUserModal) {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username|max:255',
                'password' => 'required|min:6',
                'full_name' => 'required|string|max:255',
                'employee_code' => 'required|string|unique:employees,employee_code',
                'position' => 'nullable|string',
                'hire_date' => 'nullable|date',
                'grade' => 'nullable|string',
                'address' => 'nullable|string',
                'years_of_service' => 'nullable|integer|min:0',
                'education' => 'nullable|string',
                'account_number' => 'nullable|string|max:30',
                'npwp_number' => 'nullable|string|max:30',
                'nik' => 'nullable|string|max:30',
                'branch_id' => 'required|exists:branches,id',
                'role' => 'required|string',
                'assignment_start_at' => 'required|date',
            ]);
        }

        return $rules;
    }

    public function openAssignModal()
    {
        $this->resetAssignForm();
        $this->showAssignModal = true;
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->resetAssignForm();
    }

    public function openUserModal()
    {
        $this->resetUserForm();
        $this->showUserModal = true;
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->resetUserForm();
    }

    public function saveUser()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // 1. Create Employee
            $employee = Employee::create([
                'full_name' => $this->full_name,
                'employee_code' => $this->employee_code,
                'position' => $this->position,
                'hire_date' => $this->hire_date,
                'grade' => $this->grade,
                'address' => $this->address,
                'years_of_service' => $this->years_of_service ?? 0,
                'education' => $this->education,
                'account_number' => $this->account_number,
                'npwp_number' => $this->npwp_number,
                'nik' => $this->nik,
                'is_active' => true,
            ]);

            // 2. Create User
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'employee_id' => $employee->id,
            ]);
            $user->assignRole($this->role);
            // 3. Assign to Branch (create UserBranche)
            UserBranche::create([
                'user_id' => $user->id,
                'branches_id' => $this->branch_id,
                'role' => $this->role,
                'start_at' => $this->assignment_start_at,
                'is_active' => true,
            ]);

            DB::commit();

            session()->flash('message', 'User, Employee, dan Assignment berhasil dibuat!');
            $this->closeUserModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteAssignment($id)
    {
        try {
            UserBranche::find($id)->delete();
            session()->flash('message', 'Assignment berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('message', 'Error: ' . $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            $assignment = UserBranche::find($id);
            $assignment->is_active = !$assignment->is_active;
            $assignment->save();

            session()->flash('message', 'Status berhasil diubah!');
        } catch (\Exception $e) {
            session()->flash('message', 'Error: ' . $e->getMessage());
        }
    }

    private function resetUserForm()
    {
        $this->name = '';
        $this->username = '';
        $this->password = '';
        $this->full_name = '';
        $this->employee_code = '';
        $this->position = '';
        $this->hire_date = '';
        $this->grade = '';
        $this->address = '';
        $this->years_of_service = 0;
        $this->education = '';
        $this->account_number = '';
        $this->npwp_number = '';
        $this->nik = '';
        $this->branch_id = '';
        $this->role = '';
        $this->assignment_start_at = '';
        $this->resetValidation();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }



    public function render()
    {


        $userBranches = UserBranche::with(['user', 'branch'])
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%");
            })
            ->orWhereHas('branch', function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Get all assigned user IDs
        $assignedUserIds = UserBranche::pluck('user_id')->unique()->toArray();

        // Get all employee IDs that have users
        $employeeIdsWithUsers = User::whereNotNull('employee_id')
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        return view('livewire.master.user-manage', [
            'userBranches' => $userBranches, // ← gunakan query filter

            'unassignedUsers' => User::with('employee')
                ->whereNotIn('id', $assignedUserIds)
                ->get(),

            'unassignedEmployees' => Employee::whereNotIn('id', $employeeIdsWithUsers)
                ->where('is_active', true)
                ->get(),


            'users' => User::with('employee')->get(),
            'branches' => Branch::all(),
            'roles' => Role::all(),
        ]);
    }
}
