<x-layouts.app.sidebar>

    <flux:main class="p-6">

        <div
            class="bg-white dark:bg-zinc-800 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 p-8 space-y-8">

            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                Data Payroll Karyawan
            </h2>

            <div class="overflow-x-auto rounded-lg shadow border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-100 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Nama</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Cabang</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Hari Kerja</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Gaji Pokok</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Transport</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Makan</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Tunjangan</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Bonus</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Simpanan</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Potongan</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($payrolls as $p)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                            <td class="px-4 py-2">{{ $p->userBranche->user->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $p->userBranche->branch->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-right">{{ $p->hari_kerja }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($p->transportasi, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($p->makan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($p->tunjangan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($p->bonus_revenue, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right text-red-500">{{ number_format($p->simpanan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-right text-red-500">{{ number_format($p->potongan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-right font-semibold">{{ number_format($p->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-4 text-center text-zinc-500">
                                Belum ada data payroll.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payrolls->links() }}
            </div>

        </div>

    </flux:main>



</x-layouts.app.sidebar>