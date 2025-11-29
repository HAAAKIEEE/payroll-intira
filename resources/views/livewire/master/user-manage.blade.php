<div class="p-6 bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-500 mt-1">Kelola user branch assignments dan data terkait</p>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('message') }}
        </div>
    </div>
    @endif

    {{-- Main Action Buttons --}}
    <div class="mb-6 flex gap-3">
        <button wire:click="openUserModal"
            class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
            + Tambah User & Employee Baru
        </button>
        <a href="{{ route('master-data.import-employee-user.index') }}"
            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
            + Tambah via Excel
        </a>
    </div>

    {{-- User Branch Assignments (Primary Table) --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900">User Branch Assignments</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar user yang sudah di-assign ke branch</p>



            <div class="mt-4">
                <input type="text" wire:model.live="search"
                    class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Cari nama user / username / branch...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Period</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($userBranches as $ub)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $ub->user->name ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $ub->user->username ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($ub->user && $ub->user->employee)
                            <div class="text-sm text-gray-900">{{ $ub->user->employee->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $ub->user->employee->employee_code }}</div>
                            @else
                            <span class="text-gray-400 text-sm">No employee data</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $ub->branch->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                                {{ $ub->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                {{ $ub->start_at ? \Carbon\Carbon::parse($ub->start_at)->format('d M Y') : '-' }}
                                @if($ub->end_at)
                                <br><span class="text-xs text-gray-500">to {{
                                    \Carbon\Carbon::parse($ub->end_at)->format('d M Y') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleActive({{ $ub->id }})"
                                class="px-3 py-1 rounded-full text-xs font-medium transition {{ $ub->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                {{ $ub->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="deleteAssignment({{ $ub->id }})"
                                wire:confirm="Yakin hapus assignment ini?"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada user yang di-assign ke branch</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $userBranches->links() }}
        </div>
    </div>

    {{-- Accordion untuk Unassigned Data --}}
    <div class="space-y-4">
        {{-- Unassigned Users --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <button wire:click="$toggle('showUnassignedUsers')" type="button"
                class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium text-gray-700">Users Belum Ter-assign ({{ $unassignedUsers->count()
                        }})</span>
                </div>
                <svg class="w-5 h-5 text-gray-400 transform transition {{ $showUnassignedUsers ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            @if($showUnassignedUsers)
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($unassignedUsers as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-gray-600 text-sm">{{ $user->username }}</td>
                                <td class="px-6 py-3">
                                    @if($user->employee)
                                    <span class="text-sm text-gray-600">{{ $user->employee->full_name }}</span>
                                    @else
                                    <span class="text-gray-400 text-sm">No employee</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <button wire:click="openQuickAssign({{ $user->id }})"
                                        class="px-3 py-1 bg-blue-600 text-white rounded text-xs">
                                        Quick Assign
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    Semua user sudah ter-assign
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Unassigned Employees --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <button wire:click="$toggle('showUnassignedEmployees')" type="button"
                class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium text-gray-700">Employees Belum Punya User ({{ $unassignedEmployees->count()
                        }})</span>
                </div>
                <svg class="w-5 h-5 text-gray-400 transform transition {{ $showUnassignedEmployees ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            @if($showUnassignedEmployees)
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Position</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($unassignedEmployees as $employee)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-900 font-mono text-sm">{{ $employee->employee_code }}
                                </td>
                                <td class="px-6 py-3 text-gray-900">{{ $employee->full_name }}</td>
                                <td class="px-6 py-3 text-gray-600 text-sm">{{ $employee->position ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    Semua employee sudah punya user
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal Tambah User & Employee --}}
    @if($showUserModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold text-gray-900">Tambah User & Employee Baru</h3>
                <p class="text-sm text-gray-500 mt-1">User dan Employee akan langsung ter-assign ke branch</p>
            </div>

            <div class="p-6">
                <form wire:submit.prevent="saveUser">
                    {{-- User Info --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi User
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                                <input wire:model="name" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input wire:model="username" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('username') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input wire:model="password" type="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- ini dari spatie role --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                                <select wire:model="user_role"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Employee Info --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Informasi Employee
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input wire:model="full_name" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('full_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Employee Code *</label>
                                <input wire:model="employee_code" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('employee_code') <span class="text-red-500 text-xs mt-1 block">{{ $message
                                    }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                <input wire:model="position" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('position') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Grade (Golongan)</label>
                                <input wire:model="grade" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('grade') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Education
                                    (Pendidikan)</label>
                                <input wire:model="education" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('education') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hire Date</label>
                                <input wire:model="hire_date" type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('hire_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                <input wire:model="nik" type="text" maxlength="30"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('nik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NPWP Number</label>
                                <input wire:model="npwp_number" type="text" maxlength="30"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('npwp_number') <span class="text-red-500 text-xs mt-1 block">{{ $message
                                    }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                                <input wire:model="account_number" type="text" maxlength="30"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('account_number') <span class="text-red-500 text-xs mt-1 block">{{ $message
                                    }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Years of Service</label>
                                <input wire:model="years_of_service" type="number" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('years_of_service') <span class="text-red-500 text-xs mt-1 block">{{ $message
                                    }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address (Alamat)</label>
                                <textarea wire:model="address" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                                @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Branch Assignment Info --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Assignment ke Branch
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                                <select wire:model="branch_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Pilih Branch</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @error('branch_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                          
                                <select wire:model="assignment_role" class="w-full border rounded px-3 py-2">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>

                                @error('assignment_role') <span class="text-red-500 text-xs mt-1 block">{{ $message
                                    }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input wire:model="assignment_start_at" type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('assignment_start_at') <span class="text-red-500 text-xs mt-1 block">{{ $message
                                    }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <button type="button" wire:click="closeUserModal"
                            class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Simpan & Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if($showQuickAssignModal)
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Quick Assign User</h3>

            <div class="space-y-4">
                {{-- pilih employee --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Pilih Employee</label>
                    <select wire:model="selectedEmployee" class="w-full border rounded px-3 py-2">
                        <option value="">-- pilih employee --</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">
                            {{ $emp->employee_code }} - {{ $emp->full_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('selectedEmployee') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- pilih branch --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Pilih Branch</label>
                    <select wire:model="selectedBranch" class="w-full border rounded px-3 py-2">
                        <option value="">-- pilih branch --</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedBranch') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- pilih role --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Pilih Role</label>
                    <select wire:model="selectedRole" class="w-full border rounded px-3 py-2">
                        <option value="">-- pilih role --</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRole') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button wire:click="$set('showQuickAssignModal', false)" class="px-4 py-2 bg-gray-200 rounded">
                    Batal
                </button>
                <button wire:click="saveQuickAssign" class="px-4 py-2 bg-green-600 text-white rounded">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    @endif


</div>