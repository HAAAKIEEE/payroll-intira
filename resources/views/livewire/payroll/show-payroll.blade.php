<div class="max-w-4xl mx-auto bg-white p-8 shadow-lg border border-gray-300 rounded-lg">

    <!-- Header -->
    <div class="text-center mb-6 pb-4 border-b-2 border-green-500">
        <img src="{{ asset('img/logo.png') }}" alt="Company Logo" class="w-25 mx-auto mb-2">
        <h1 class="font-bold text-xl text-gray-800">PT SOLUSI INTIRA SEJAHTERA</h1>
        <p class="text-sm text-gray-600">Head Office Jl. Adhyaksa No.018 RT.026 RW.001</p>
        <p class="text-sm text-gray-600">Kel. Banjarmain, Kec. Banjarmasin Utara</p>
        <p class="text-sm text-gray-600">Kota Banjarmasin, Kalimantan Selatan</p>
    </div>

    @if (!$payroll)
    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded">
        <strong>Perhatian:</strong> Anda belum memiliki payroll bulan ini.
    </div>
    @else

    <!-- Data Karyawan -->
    <table class="w-full mb-6 text-gray-700">
        <tr class="border-b border-gray-200">
            <td class="font-semibold w-40 py-2">NAMA KARYAWAN</td>
            <td class="py-2">: {{ $payroll->userBranche->user->name }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <td class="font-semibold py-2">CABANG</td>
            <td class="py-2">: {{ $payroll->userBranche->branch->name }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <td class="font-semibold py-2">GOLONGAN</td>
            <td class="py-2">: {{ $payroll->userBranche->user->golongan ?? '-' }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <td class="font-semibold py-2">PERIODE</td>
            <td class="py-2">: {{ $payroll->periode }}</td>
        </tr>
    </table>

    <hr class="my-4 border-gray-300">

    <!-- Rincian Payroll -->
   

    <table class="w-full text-sm">
        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold text-gray-700">GAJI POKOK</td>
            <td></td>
            <td class="text-right py-2 text-gray-800">
                {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold text-gray-700">TRANSPORT</td>
            <td class="py-2 font-semibold text-gray-600">
                ({{ $payroll->hari_kerja }} HARI )
            </td>
            <td class="text-right py-2 text-gray-800">
                {{ number_format($payroll->transportasi, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold text-gray-700">MAKAN</td>
            <td class="py-2 font-semibold text-gray-600">
                ({{ $payroll->hari_kerja }} HARI)
            </td>
            <td class="text-right py-2 text-gray-800">
                 {{ number_format($payroll->makan, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="bg-green-100 border-b border-green-200">
            <td class="py-2 font-semibold text-green-800">BONUS REVENUE</td>
            <td class="py-2 font-semibold text-green-800">
                <span class="mr-5">

                    {{ number_format($payroll->bonus_revenue, 0, ',', '.') }}
                </span>
             ( {{ rtrim(rtrim(number_format($payroll->revenue_persentase, 2, '.', ''), '0'), '.') }}%
             )
            </td>
            <td class="text-right py-2 font-semibold text-green-800">
                {{ number_format($payroll->total_revenue, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold text-gray-700">TUNJANGAN</td>
            <td></td>
            <td class="text-right py-2 text-gray-800">
                {{ number_format($payroll->tunjangan, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="text-red-600 border-b border-gray-200">
            <td class="py-2 font-semibold">POTONGAN</td>
            <td></td>
            <td class="text-right py-2 font-semibold">
                -{{ number_format($payroll->potongan, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold text-gray-700">SIMPANAN</td>
            <td></td>
            <td class="text-right py-2 text-gray-800">
                -{{ number_format($payroll->simpanan ?? 0, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold text-gray-700">KPI</td>
            <td class="py-2 font-semibold text-gray-600">
                {{-- {{ rtrim(rtrim(number_format($payroll->kpi_persentase, 2, '.', ''), '0'), '.') }}% --}}
<span class="mr-5">
    ({{ $payroll->kpi_persentase * 100 }}%)

</span>

                {{ number_format($payroll->kpi, 0, ',', '.') }}

            </td>
           
            <td class="text-right py-2 text-gray-800">
                {{ number_format($payroll->total_kpi, 0, ',', '.') }}
            </td>
        </tr>
        <tr class="border-t-2 border-gray-400 font-bold text-lg bg-gray-50">
            <td class="py-3 text-gray-800">GRAND TOTAL</td>
            <td></td>
            <td class="text-right py-3 text-gray-800">
                {{ number_format($payroll->grand_total, 0, ',', '.') }}
            </td>
        </tr>


        <tr class="bg-yellow-300 font-bold text-lg border-2 border-yellow-400">
            <td class="py-3 text-gray-900">TAKE HOME PAY</td>
            <td></td>
            <td class="text-right py-3 text-gray-900">
                {{ number_format($payroll->take_home_pay, 0, ',', '.') }}
            </td>
        </tr>

    </table>


    {{-- Rincian Tunjangan / Potongan Tambahan --}}
    @if ($pieces->count())
    <h3 class="mt-6 font-bold text-lg border-b-2 border-green-500 pb-2 text-gray-800">
        RINCIAN TUNJANGAN / POTONGAN TAMBAHAN
    </h3>

    <table class="w-full text-sm mt-3 border border-gray-300">
        <thead class="bg-green-100 font-semibold text-gray-800">
            <tr>
                <th class="py-3 px-3 text-left border border-gray-300">Tanggal</th>
                <th class="py-3 px-3 text-left border border-gray-300">Kategori</th>
                <th class="py-3 px-3 text-right border border-gray-300">Kesejahteraan</th>
                <th class="py-3 px-3 text-right border border-gray-300">Komunikasi</th>
                <th class="py-3 px-3 text-right border border-gray-300">Tunjangan</th>
                <th class="py-3 px-3 text-right border border-gray-300">Potongan</th>
                <th class="py-3 px-3 text-left border border-gray-300">Keterangan</th>
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
            <tr class="hover:bg-gray-50">
                <td class="py-2 px-3 border border-gray-300">{{ $piece->tanggal ?? '-' }}</td>
                <td class="py-2 px-3 border border-gray-300">{{ $piece->kategori ?? '-' }}</td>
                <td class="py-2 px-3 text-right border border-gray-300">
                    {{ $piece->kesejahteraan ? number_format($piece->kesejahteraan, 0, ',', '.') : '-' }}
                </td>
                <td class="py-2 px-3 text-right border border-gray-300">
                    {{ $piece->komunikasi ? number_format($piece->komunikasi, 0, ',', '.') : '-' }}
                </td>
                <td class="py-2 px-3 text-right border border-gray-300">
                    {{ $piece->tunjangan ? number_format($piece->tunjangan, 0, ',', '.') : '-' }}
                </td>
                <td class="py-2 px-3 text-right border border-gray-300 text-red-600 font-semibold">
                    {{ $piece->potongan ? number_format($piece->potongan, 0, ',', '.') : '-' }}
                </td>
                <td class="py-2 px-3 border border-gray-300">{{ $piece->keterangan ?? '-' }}</td>
            </tr>
            @endforeach

            {{-- Total Row --}}
            <tr class="bg-green-50 font-bold border-t-2 border-green-300">
                <td class="py-3 px-3 border border-gray-300 text-gray-800"></td>

                <td class="py-3 px-3 border border-gray-300 text-gray-800">TOTAL</td>
                <td class="py-3 px-3 text-right border border-gray-300 text-gray-800">
                    {{ number_format($totalKesejahteraan, 0, ',', '.') }}
                </td>
                <td class="py-3 px-3 text-right border border-gray-300 text-gray-800">
                    {{ number_format($totalKomunikasi, 0, ',', '.') }}
                </td>
                <td class="py-3 px-3 text-right border border-gray-300 text-gray-800">
                    {{ number_format($totalTunjangan, 0, ',', '.') }}
                </td>
                <td class="py-3 px-3 text-right border border-gray-300 text-red-600">
                    {{ number_format($totalPotongan, 0, ',', '.') }}
                </td>
                <td class="py-3 px-3 border border-gray-300"></td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="mt-10 text-right">
        <p class="text-gray-700 mb-1">Banjarmasin, {{ now()->isoFormat('D MMMM Y') }}</p>
        <br><br><br>
        {{-- <img src="/signature.png" class="w-32 mx-auto"> --}}
        <p class="font-semibold text-gray-800">AULIA RISKI YUSPIHANI YUSRAN</p>
        <p class="text-sm text-gray-600">HO SDM</p>
    </div>

    @endif

</div>