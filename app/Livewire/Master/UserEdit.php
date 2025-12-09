<?php

// namespace App\Livewire\Master;

// use Livewire\Component;
// use App\Models\User;
// use App\Models\UserBranche;
// use App\Models\Employee;
// use App\Models\Branch;
// use Spatie\Permission\Models\Role;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Log;
// use Carbon\Carbon;

// class UserEdit extends Component
// {
//     // user id
//     public $id;

//     // Form Properties - User
//     // public $name, $username, $password, $user_role;

//     // // Form Properties - Employee
//     // public $full_name, $employee_code, $position, $hire_date;
//     // public $grade, $address, $years_of_service = 0, $education;
//     // public $account_number, $npwp_number, $nik;

//     // // Form Properties - Assignment
//     // public $branch_id, $assignment_role, $assignment_start_at;

//     public $branch;
//     public $userBranche;
//     public $user;
//     public $employee;

//     public $branches = [];
//     public $formData = [];
//     public $roles = [];

//     public $newPassword;
//     // public $employee = [];

//     // public $user = [];

//     public function mount($id)
//     {
//         $this->id = $id;
//         $this->loadData();
//     }

//     public function loadData()
//     {
//         // Query langsung mengembalikan object, bukan Collection
//         $this->userBranche = UserBranche::with(['user.employee', 'branch'])
//             ->where('id', $this->id)
//             ->first(); // Ganti get() dengan first()

//         $this->roles = Role::all();
//         $this->branches = Branch::all();
//         // Lalu pisahkan seperti sebelumnya
//         if ($this->userBranche) {
//             $this->user = $this->userBranche->user;
//             $this->employee = $this->user->employee;
//             $this->branch = $this->userBranche->branch;
//         }

//         if ($this->userBranche && $this->user) {
//             // Data dari UserBranche
//             $this->formData['user_id'] = $this->userBranche->user_id;
//             $this->formData['role'] = $this->userBranche->role;
//             $this->formData['is_active'] = $this->userBranche->is_active;

//             // Konversi Carbon/DateTime ke format Y-m-d untuk input date
//             $startAt = $this->userBranche->start_at
//                 ? Carbon::parse($this->userBranche->start_at)->format('Y-m-d')
//                 : null;

//             $this->formData['start_at'] = $startAt;
//             $this->formData['end_at'] = $this->userBranche->end_at;

//             // Data dari User
//             $this->formData['name'] = $this->user->name;
//             $this->formData['username'] = $this->user->username;
//             // $this->formData['user_role'] = $this->user->user_role;
//             // $this->formData['email'] = $this->user->email ?? ''; // jika ada

//             // Data dari Employee (melalui relasi user->employee)
//             if ($this->employee) {
//                 $this->formData['full_name'] = $this->employee->full_name;
//                 $this->formData['employee_code'] = $this->employee->employee_code;
//                 $this->formData['grade'] = $this->employee->grade;
//                 $this->formData['address'] = $this->employee->address;
//                 $this->formData['hire_date'] = $this->employee->hire_date;
//                 $this->formData['years_of_service'] = $this->employee->years_of_service;
//                 $this->formData['education'] = $this->employee->education;
//                 $this->formData['employee_code'] = $this->employee->employee_code;
//                 $this->formData['account_number'] = $this->employee->account_number;
//                 $this->formData['npwp_number'] = $this->employee->npwp_number;
//                 $this->formData['nik'] = $this->employee->nik;
//                 $this->formData['position'] = $this->employee->position;
//                 $this->formData['is_active'] = $this->employee->is_active;
//             }

//             // Data dari Branch
//             if ($this->branch) {
//                 $this->formData['branch_id'] = $this->branch->id;
//                 // $this->formData['branch_name'] = $this->branch->name;
//                 // $this->formData['branch_address'] = $this->branch->address;
//                 // $this->formData['branch_is_active'] = $this->branch->is_active;
//                 // $this->formData['branch_region_id'] = $this->branch->region_id;
//             }
//         }
//     }

//     public function updateUser()
//     {
//         dd('datauser', $this->formData, $this->newPassword);
//         // userbranch need id, user_id, branches_id, role, start_at, end_at
//         $userBranchesValidated = $this->validate([
//             'formData.user_id' => 'required|exists:user,id',
//             'formData.branch_id' => 'required|exist:branches,id',
//             'formData.role' => 'required|string|exists:roles,name',
//             'formData.start_at' => 'required|date',
//             'formData.end_at' => 'required|date',
//         ]);

//         //user need id, name, username, newPassword if change, employee_id
//         $userValidated = $this->validate([
//             'name' => 'required|string|max:255',
//             'username' => 'required|string|max:255|unique:users,username,except,',
//             $this->user->id,
//             'newPassword' => 'nullable|min:6',
//             'employee_id' => 'required|exists:employees,id'
//         ]);

//         // employee need, id, full_name, grade, address, hire_date, years_of_service, education, employee_code, account_number, npwp_number, nik, position
//         $employeeValidated = $this->validate([
//             'full_name' => 'required|string|max:255',
//             'employee_code' => 'required|string|unique:employees,employee_code',
//             'position' => 'nullable|string',
//             'hire_date' => 'nullable|date',
//             'grade' => 'nullable|string',
//             'address' => 'nullable|string',
//             'years_of_service' => 'nullable|integer|min:0',
//             'education' => 'nullable|string',
//             'account_number' => 'nullable|string|max:30',
//             'npwp_number' => 'nullable|string|max:30',
//             'nik' => 'nullable|string|max:30',
//         ]);

//         dd('validated', $userBranchesValidated, $userValidated, $employeeValidated);
//     }

//     public function render()
//     {
//         return view('livewire.master.user-edit', [
//             'id' => $this->id,
//             'userBranche' => $this->userBranche,
//             'user' => $this->user,
//             'employee' => $this->employee,
//             'branch' => $this->branch,
//             'branches' => $this->branches,
//             'roles' => $this->roles,
//         ]);
//     }
// }

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\UserBranche;
use App\Models\Employee;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserEdit extends Component
{
    public $id;

    public $branch;
    public $userBranche;
    public $user;
    public $employee;

    public $branches = [];
    public $roles = [];

    public $formData = [];
    public $newPassword;

    public function mount($id)
    {
        $this->id = $id;
        $this->loadData();
    }

    public function loadData()
    {
        $this->userBranche = UserBranche::with(['user.employee', 'branch'])
            ->find($this->id);

        $this->roles = Role::all();
        $this->branches = Branch::all();

        if (!$this->userBranche) {
            return;
        }

        $this->user = $this->userBranche->user;
        $this->employee = $this->user->employee;
        $this->branch = $this->userBranche->branch;

        // USERBRANCHE
        $this->formData['user_id'] = $this->userBranche->user_id;
        $this->formData['branch_id'] = $this->userBranche->branches_id;
        $this->formData['role'] = $this->userBranche->role;
        $this->formData['is_active'] = $this->userBranche->is_active;

        $this->formData['start_at'] = $this->userBranche->start_at
            ? Carbon::parse($this->userBranche->start_at)->format('Y-m-d')
            : null;

        $this->formData['end_at'] = $this->userBranche->end_at;

        // USER DATA
        $this->formData['name'] = $this->user->name;
        $this->formData['username'] = $this->user->username;
        $this->formData['employee_id'] = $this->employee->id ?? null;

        // EMPLOYEE DATA
        if ($this->employee) {
            $this->formData['full_name'] = $this->employee->full_name;
            $this->formData['employee_code'] = $this->employee->employee_code;
            $this->formData['grade'] = $this->employee->grade;
            $this->formData['address'] = $this->employee->address;
            $this->formData['hire_date'] = $this->employee->hire_date;
            $this->formData['years_of_service'] = $this->employee->years_of_service;
            $this->formData['education'] = $this->employee->education;
            $this->formData['account_number'] = $this->employee->account_number;
            $this->formData['npwp_number'] = $this->employee->npwp_number;
            $this->formData['nik'] = $this->employee->nik;
            $this->formData['position'] = $this->employee->position;
            $this->formData['employee_is_active'] = $this->employee->is_active;
        }
    }

    public function updateUser()
    {
        // VALIDASI USERBRANCHE
        $userBranchRules = $this->validate([
            'formData.user_id'   => 'required|exists:users,id',
            'formData.branch_id' => 'required|exists:branches,id',
            'formData.role'      => 'required|string|exists:roles,name',
            'formData.start_at'  => 'required|date',
            'formData.end_at'    => 'nullable|date',
        ]);

        // VALIDASI USER
        $userRules = $this->validate([
            'formData.name' => 'required|string|max:255',
            'formData.username' => 'required|string|max:255|unique:users,username,' . $this->user->id,
            'newPassword' => 'nullable|min:6',
            'formData.employee_id' => 'required|exists:employees,id',
        ]);

        // VALIDASI EMPLOYEE
        $employeeRules = $this->validate([
            'formData.full_name' => 'required|string|max:255',
            'formData.employee_code' => 'required|string|unique:employees,employee_code,' . $this->employee->id,
            'formData.position' => 'nullable|string',
            'formData.hire_date' => 'nullable|date',
            'formData.grade' => 'nullable|string',
            'formData.address' => 'nullable|string',
            'formData.years_of_service' => 'nullable|integer|min:0',
            'formData.education' => 'nullable|string',
            'formData.account_number' => 'nullable|string|max:30',
            'formData.npwp_number' => 'nullable|string|max:30',
            'formData.nik' => 'nullable|string|max:30',
        ]);

        DB::beginTransaction();

        try {

            // UPDATE USER
            $this->user->update([
                'name' => $this->formData['name'],
                'username' => $this->formData['username'],
                'password' => $this->newPassword
                    ? Hash::make($this->newPassword)
                    : $this->user->password,
            ]);

            $this->user->syncRoles([]); // remove all existing roles
            $this->user->assignRole($this->formData['role']); // assign new role

            // UPDATE EMPLOYEE
            $this->employee->update([
                'full_name' => $this->formData['full_name'],
                'employee_code' => $this->formData['employee_code'],
                'position' => $this->formData['position'],
                'hire_date' => $this->formData['hire_date'],
                'grade' => $this->formData['grade'],
                'address' => $this->formData['address'],
                'years_of_service' => $this->formData['years_of_service'],
                'education' => $this->formData['education'],
                'account_number' => $this->formData['account_number'],
                'npwp_number' => $this->formData['npwp_number'],
                'nik' => $this->formData['nik'],
            ]);

            // UPDATE USER BRANCH
            $this->userBranche->update([
                'branches_id' => $this->formData['branch_id'],
                'role' => $this->formData['role'],
                'start_at' => Carbon::parse($this->formData['start_at']),
                'end_at' => $this->formData['end_at']
                    ? Carbon::parse($this->formData['end_at'])
                    : null,
            ]);

            DB::commit();

            session()->flash('message', 'Data berhasil diperbarui!');
            $this->loadData();
            $this->redirect('/users');
        } catch (\Exception $e) {

            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
            Log::error($e);
        }
    }

    public function render()
    {
        return view('livewire.master.user-edit', [
            'id' => $this->id,
            'userBranche' => $this->userBranche,
            'user' => $this->user,
            'employee' => $this->employee,
            'branch' => $this->branch,
            'branches' => $this->branches,
            'roles' => $this->roles,
        ]);
    }
}
