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
use Illuminate\Support\Facades\Log;

class UserManage extends Component
{
    use WithPagination;

    // Search & Filter
    public $search = '';

    // Quick Assign Properties
    public $quickAssignUserId;
    public $selectedEmployee;
    public $selectedBranch;
    public $selectedRole;
    public $showQuickAssignModal = false;

    // Form Properties - User
    public $name, $username, $password, $user_role;

    // Form Properties - Employee
    public $full_name, $employee_code, $position, $hire_date;
    public $grade, $address, $years_of_service = 0, $education;
    public $account_number, $npwp_number, $nik;

    // Form Properties - Assignment
    public $branch_id, $assignment_role, $assignment_start_at;

    // Modal States
    public $showUserModal = false;
    public $showUnassignedUsers = false;
    public $showUnassignedEmployees = false;

    // Dropdown Data
    public $employees = [];
    public $branches = [];
    public $roles = [];

    public function mount()
    {
        $this->loadDropdownData();
    }

    private function loadDropdownData()
    {
        $this->branches = Branch::all();
        $this->roles = Role::all();
        $this->employees = Employee::whereDoesntHave('user')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // ===== QUICK ASSIGN =====
    public function openQuickAssign($userId)
    {
        $this->quickAssignUserId = $userId;
        $this->showQuickAssignModal = true;
        $this->loadDropdownData();
    }

    public function saveQuickAssign()
    {
        $this->validate([
            'selectedEmployee' => 'required|exists:employees,id',
            'selectedBranch' => 'required|exists:branches,id',
            'selectedRole' => 'required|exists:roles,name',
        ]);

        try {
            DB::beginTransaction();

            // Link user ke employee
            User::where('id', $this->quickAssignUserId)
                ->update(['employee_id' => $this->selectedEmployee]);

            // Create assignment
            UserBranche::create([
                'user_id' => $this->quickAssignUserId,
                'branches_id' => $this->selectedBranch,
                'role' => $this->selectedRole,
                'is_active' => true,
                'start_at' => now(),
            ]);

            DB::commit();
            session()->flash('message', 'User berhasil di-assign.');
            $this->closeQuickAssignModal();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error quick assign: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    private function closeQuickAssignModal()
    {
        $this->showQuickAssignModal = false;
        $this->reset(['quickAssignUserId', 'selectedEmployee', 'selectedBranch', 'selectedRole']);
    }

    // ===== USER MODAL =====
    public function openUserModal()
    {
        $this->resetUserForm();
        $this->showUserModal = true;
        $this->loadDropdownData();
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->resetUserForm();
    }

    public function saveUser()
    {
        // Log untuk debugging
        Log::info('Attempting to save user', [
            'name' => $this->name,
            'username' => $this->username,
            'employee_code' => $this->employee_code,
        ]);

        // Validasi
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|min:6',
            'user_role' => 'required|string|exists:roles,name',
            
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
            'assignment_role' => 'required|string|exists:roles,name',
            'assignment_start_at' => 'required|date',
        ]);

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

            Log::info('Employee created', ['id' => $employee->id]);

            // 2. Create User
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'employee_id' => $employee->id,
            ]);

            Log::info('User created', ['id' => $user->id]);

            // 3. Assign Role
            $user->assignRole($this->user_role);
            Log::info('Role assigned', ['role' => $this->user_role]);

            // 4. Create UserBranche
            $userBranch = UserBranche::create([
                'user_id' => $user->id,
                'branches_id' => $this->branch_id,
                'role' => $this->assignment_role,
                'start_at' => $this->assignment_start_at,
                'is_active' => true,
            ]);

            Log::info('UserBranche created', ['id' => $userBranch->id]);

            DB::commit();

            session()->flash('message', 'User, Employee, dan Assignment berhasil dibuat!');
            $this->closeUserModal();
            $this->resetPage();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating user:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    private function resetUserForm()
    {
        $this->reset([
            'name', 'username', 'password', 'user_role',
            'full_name', 'employee_code', 'position', 'hire_date',
            'grade', 'address', 'education',
            'account_number', 'npwp_number', 'nik',
            'branch_id', 'assignment_role', 'assignment_start_at'
        ]);
        
        $this->years_of_service = 0;
        $this->resetValidation();
    }

    // ===== USER BRANCH ACTIONS =====
    public function deleteAssignment($id)
    {
        try {
            UserBranche::findOrFail($id)->delete();
            session()->flash('message', 'Assignment berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting assignment: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus assignment: ' . $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            $assignment = UserBranche::findOrFail($id);
            $assignment->is_active = !$assignment->is_active;
            $assignment->save();

            session()->flash('message', 'Status berhasil diubah!');
        } catch (\Exception $e) {
            Log::error('Error toggling active: ' . $e->getMessage());
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    // ===== RENDER =====
    public function render()
    {
        // Get assigned user IDs
        $assignedUserIds = UserBranche::pluck('user_id')->unique()->toArray();

        // Get employee IDs that have users
        $employeeIdsWithUsers = User::whereNotNull('employee_id')
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        // User Branches with search
        $userBranches = UserBranche::with(['user.employee', 'branch'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('username', 'like', "%{$this->search}%");
                })
                ->orWhereHas('branch', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.master.user-manage', [
            'userBranches' => $userBranches,
            'unassignedUsers' => User::with('employee')
                ->whereNotIn('id', $assignedUserIds)
                ->get(),
            'unassignedEmployees' => Employee::whereNotIn('id', $employeeIdsWithUsers)
                ->where('is_active', true)
                ->get(),
            'users' => User::with('employee')->get(),
            'branches' => $this->branches,
            'roles' => $this->roles,
        ]);
    }
}