<div class="p-6 bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-500 mt-1">Kelola user dan penugasan cabang</p>
    </div>

    {{-- Flash Message --}}
   @if (session('success'))
        <div class="px-4 py-3 mb-4 border rounded-lg bg-emerald-100 border-emerald-300 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if (session('error'))
        <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-300 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    {{-- Action --}}
    <div class="mb-6 flex gap-3">
        <button wire:click="openUserModal"
            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
            + Tambah User
        </button>

        <a href="{{ route('master-data.import-employee-user.index') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Import Excel
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Username</th>
                    <th class="px-4 py-3 text-left">Cabang</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($userBranches as $ub)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $ub->user->name }}</td>
                        <td class="px-4 py-3">{{ $ub->user->username }}</td>
                        <td class="px-4 py-3">{{ $ub->branch->name }}</td>
                        <td class="px-4 py-3">{{ $ub->role }}</td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="px-3 py-1 text-xs rounded-full
                                {{ $ub->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $ub->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $userBranches->links() }}
        </div>
    </div>
</div>
