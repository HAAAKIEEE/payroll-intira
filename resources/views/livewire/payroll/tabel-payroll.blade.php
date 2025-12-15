<div class="p-6 bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Data Payroll</h1>
        <p class="text-gray-500 text-sm">Daftar gaji karyawan per periode</p>
    </div>

    {{-- FILTER --}}
    <div class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">

        {{-- Nama --}}
        <input
            type="text"
            wire:model="search"
            placeholder="Cari nama karyawan..."
            class="px-4 py-2 border rounded-lg"
        >

        {{-- Cabang --}}
        <select wire:model="branchId" class="px-4 py-2 border rounded-lg">
            <option value="">Semua Cabang</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>

        {{-- Periode --}}
        <select wire:model="periode" class="px-4 py-2 border rounded-lg">
            <option value="">Semua Periode</option>
            @foreach ($periodes as $p)
                <option value="{{ $p }}">{{ $p }}</option>
            @endforeach
        </select>

        {{-- BUTTON --}}
        <button
            wire:click="applyFilter"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
            Filter
        </button>

        <button
            wire:click="resetFilter"
            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400"
        >
            Reset
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Cabang</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Golongan</th>
                    <th class="px-4 py-3 text-right">Gaji Pokok</th>
                    <th class="px-4 py-3 text-right">THP</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($payrolls as $payroll)
                    <tr class="border-t">
                        <td class="px-4 py-2">
                            {{ $payroll->userBranche->user->name }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $payroll->userBranche->branch->name }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $payroll->periode }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $payroll->golongan }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right font-bold text-green-600">
                            Rp {{ number_format($payroll->take_home_pay, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $payrolls->links() }}
        </div>
    </div>

</div>
