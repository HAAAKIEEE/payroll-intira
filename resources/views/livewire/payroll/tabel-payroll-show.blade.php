<div class="p-6 bg-gray-50 min-h-screen">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Detail Payroll</h1>
        <p class="text-gray-500 text-sm">
            Periode {{ $payroll->periode }}
        </p>
    </div>

    {{-- DATA UTAMA --}}
    <div class="bg-white rounded-xl shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KIRI --}}
        <div class="space-y-2">
            <p><strong>Nama:</strong> {{ $payroll->userBranche->user->name }}</p>
            <p><strong>Cabang:</strong> {{ $payroll->userBranche->branch->name }}</p>
            <p><strong>Golongan:</strong> {{ $payroll->golongan }}</p>
            <p><strong>Hari Kerja:</strong> {{ $payroll->hari_kerja }}</p>
        </div>

        {{-- KANAN --}}
        <div class="space-y-2">
            <p><strong>Periode:</strong> {{ $payroll->periode }}</p>
            <p><strong>Jumlah Cabang Dipegang:</strong> {{ $payroll->jumlah_cabang_dipegang }}</p>
            <p><strong>Revenue %:</strong> {{ $payroll->revenue_persentase }}%</p>
            <p><strong>KPI %:</strong> {{ $payroll->kpi_persentase }}%</p>
        </div>
    </div>

    {{-- KOMPONEN GAJI --}}
    <div class="mt-6 bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-lg mb-4">Komponen Gaji</h2>

        <table class="w-full text-sm">
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-right">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Transportasi</td>
                <td class="text-right">Rp {{ number_format($payroll->transportasi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Makan</td>
                <td class="text-right">Rp {{ number_format($payroll->makan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td class="text-right">Rp {{ number_format($payroll->tunjangan, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- REVENUE & BONUS --}}
    <div class="mt-6 bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-lg mb-4">Revenue & Bonus</h2>

        <table class="w-full text-sm">
            <tr>
                <td>Total Revenue</td>
                <td class="text-right">Rp {{ number_format($payroll->total_revenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bonus Revenue</td>
                <td class="text-right">Rp {{ number_format($payroll->bonus_revenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- KPI --}}
    <div class="mt-6 bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-lg mb-4">KPI</h2>

        <table class="w-full text-sm">
            <tr>
                <td>Nilai KPI</td>
                <td class="text-right">{{ $payroll->kpi }}</td>
            </tr>
            <tr>
                <td>Total KPI</td>
                <td class="text-right">Rp {{ number_format($payroll->total_kpi, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- POTONGAN --}}
    <div class="mt-6 bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-lg mb-4">Potongan & Simpanan</h2>

        <table class="w-full text-sm">
            <tr>
                <td>Simpanan</td>
                <td class="text-right">Rp {{ number_format($payroll->simpanan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Potongan</td>
                <td class="text-right text-red-600">
                    Rp {{ number_format($payroll->potongan, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- TOTAL AKHIR --}}
    <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-6">
        <h2 class="font-semibold text-lg mb-4 text-green-800">Total Akhir</h2>

        <div class="flex justify-between text-lg font-bold text-green-700">
            <span>Take Home Pay</span>
            <span>
                Rp {{ number_format($payroll->take_home_pay, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- BACK --}}
    <div class="mt-6">
        <a href="{{ route('payroll.manage') }}"
           class="text-blue-600 hover:underline">
            ← Kembali ke tabel payroll
        </a>
    </div>

</div>
