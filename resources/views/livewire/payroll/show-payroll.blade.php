<div class="max-w-4xl mx-auto bg-white p-8 shadow-lg border border-gray-300">

    <!-- Header -->
    <div class="text-center mb-6">
        <img src="{{ asset('img/logo.png') }}" alt="Company Logo" class="w-25 mx-auto mb-2">
        <h1 class="font-bold text-xl">PT SOLUSI INTIRA SEJAHTERA</h1>
        @role('Head Office')
        <p class="text-sm">Head Office Jl. Kompleks Agraria II No.045 RT.025 RW.003  
            Kel. Telaga Biru, Kec. Banjarmasin Barat  
            Kota Banjarmasin, Kalimantan Selatan</p>
        @endrole
    </div>

    @if (!$payroll)
        <div class="p-4 bg-yellow-100 border text-yellow-800">
            Anda belum memiliki payroll bulan ini.
        </div>
    @else

    <!-- Data Karyawan -->
    <table class="w-full mb-6">
        <tr><td class="font-semibold w-40">NAMA KARYAWAN</td><td>: {{ $payroll->userBranche->user->name }}</td></tr>
        <tr><td class="font-semibold">CABANG</td><td>: {{ $payroll->userBranche->branch->name }}</td></tr>
        <tr><td class="font-semibold">GOLONGAN</td><td>: {{ $payroll->userBranche->user->golongan ?? '-' }}</td></tr>
        <tr><td class="font-semibold">PERIODE</td><td>: {{ $payroll->period }}</td></tr>
    </table>

    <hr class="my-3 border-gray-400">

    <!-- Rincian Payroll -->
    <table class="w-full text-sm">
        <tr>
            <td class="py-1 font-semibold">GAJI POKOK</td>
            <td class="text-right">{{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td class="py-1 font-semibold">TRANSPORT ({{ $payroll->hari_kerja }} HARI)</td>
            <td class="text-right">{{ number_format($payroll->transportasi, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td class="py-1 font-semibold">MAKAN ({{ $payroll->hari_kerja }} HARI)</td>
            <td class="text-right">{{ number_format($payroll->makan, 0, ',', '.') }}</td>
        </tr>

        <tr class="bg-green-100 font-semibold">
            <td class="py-1">BONUS REVENUE</td>
            <td class="text-right">{{ number_format($payroll->bonus_revenue, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td class="py-1 font-semibold">TUNJANGAN</td>
            <td class="text-right">{{ number_format($payroll->tunjangan, 0, ',', '.') }}</td>
        </tr>

        <tr class="text-red-600">
            <td class="py-1 font-semibold">POTONGAN</td>
            <td class="text-right">-{{ number_format($payroll->potongan, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td class="py-1 font-semibold">SIMPANAN</td>
            <td class="text-right">-{{ number_format($payroll->simpanan ?? 0, 0, ',', '.') }}</td>
        </tr>

        <tr class="border-t border-gray-500 font-bold text-lg bg-gray-100">
            <td class="py-2">GRAND TOTAL</td>
            <td class="text-right">{{ number_format($payroll->total, 0, ',', '.') }}</td>
        </tr>

        <tr class="bg-yellow-300 font-bold text-lg">
            <td class="py-2">TAKE HOME PAY</td>
            <td class="text-right">{{ number_format($payroll->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Rincian Tunjangan / Potongan Tambahan --}}
    @if ($pieces->count())
        <h3 class="mt-6 font-bold text-lg border-b border-gray-500 pb-2">
            RINCIAN TUNJANGAN / POTONGAN TAMBAHAN
        </h3>

        <table class="w-full text-sm mt-2 border border-gray-300">
            <thead class="bg-gray-200 font-semibold">
                <tr>
                    <th class="py-2 px-2 text-left border">Kategori</th>
                    <th class="py-2 px-2 text-right border">Kesejahteraan</th>
                    <th class="py-2 px-2 text-right border">Komunikasi</th>
                    <th class="py-2 px-2 text-right border">Tunjangan</th>
                    <th class="py-2 px-2 text-right border">Potongan</th>
                    <th class="py-2 px-2 text-left border">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalKesejahteraan = 0;
                    $totalKomunikasi = 0;
                    $totalTunjangan = 0;
                    $totalPotongan = 0;
                @endphp

                @foreach ($pieces as $piece)
                    @php
                        $totalKesejahteraan += $piece->kesejahteraan ?? 0;
                        $totalKomunikasi += $piece->komunikasi ?? 0;
                        $totalTunjangan += $piece->tunjangan ?? 0;
                        $totalPotongan += $piece->potongan ?? 0;
                    @endphp
                    <tr>
                        <td class="py-1 px-2 border">{{ $piece->kategori ?? '-' }}
                            {{ $piece->id ?? '-' }}
                        </td>
                        <td class="py-1 px-2 text-right border">
                            {{ $piece->kesejahteraan ? number_format($piece->kesejahteraan, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-1 px-2 text-right border">
                            {{ $piece->komunikasi ? number_format($piece->komunikasi, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-1 px-2 text-right border">
                            {{ $piece->tunjangan ? number_format($piece->tunjangan, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-1 px-2 text-right border text-red-600">
                            {{ $piece->potongan ? number_format($piece->potongan, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-1 px-2 border">{{ $piece->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach

                {{-- Total Row --}}
                <tr class="bg-gray-100 font-bold">
                    <td class="py-2 px-2 border">TOTAL</td>
                    <td class="py-2 px-2 text-right border">
                        {{ number_format($totalKesejahteraan, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-2 text-right border">
                        {{ number_format($totalKomunikasi, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-2 text-right border">
                        {{ number_format($totalTunjangan, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-2 text-right border text-red-600">
                        {{ number_format($totalPotongan, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-2 border"></td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="mt-10 text-right">
        Banjarmasin, {{ now()->isoFormat('D MMMM Y') }}
        <br><br><br>
        <img src="/signature.png" class="w-32 mx-auto">
        <p class="font-semibold">ACIA RISKI YUSPIHANI</p>
        <p class="text-sm">HO SDM</p>
    </div>

    @endif

</div>