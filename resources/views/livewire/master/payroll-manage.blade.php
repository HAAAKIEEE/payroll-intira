<div>
    @if (session()->has('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('errors_import'))
    <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
        <strong>Beberapa baris gagal diimport:</strong>
        <ul class="mt-2 list-disc ml-5">
            @foreach (session('errors_import') as $err)
            <li class="mb-2">
                <strong>Baris: {{ $err['row'] }}</strong><br>
                Alasan: {{ $err['reason'] }}<br>

                <details class="mt-1 text-sm text-gray-600">
                    <summary class="cursor-pointer">Detail Data</summary>
                    <pre class="mt-2 bg-gray-100 p-2 rounded">
{{ json_encode($err['data'], JSON_PRETTY_PRINT) }}
        </pre>
                </details>
            </li>

            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                Upload File Excel
            </h2>
        </div>

        <form wire:submit.prevent="import" enctype="multipart/form-data" id="importForm">
            <div
                class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                    Periode Payroll
                </label>

                <div
                    class="flex items-center gap-3 border border-zinc-300 dark:border-zinc-600 rounded-lg px-4 py-3 focus-within:ring-2 focus-within:ring-green-500 transition">
                    <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                    <input type="month" wire:model.defer="periode" required
                        class="w-full bg-transparent border-none focus:outline-none text-zinc-800 dark:text-zinc-100">
                </div>

                @error('periode')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg p-8 text-center hover:border-blue-500 dark:hover:border-blue-500 transition-colors duration-200 mb-6"
                id="dropZone">

                <input type="file" wire:model="file" id="fileInput" accept=".xlsx,.xls" class="hidden">

                <label for="fileInput" class="cursor-pointer block">
                    <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600 mx-auto mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-lg text-zinc-600 dark:text-zinc-300 mb-2">
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">Klik untuk upload</span> atau drag
                        & drop
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-500">Format: .xlsx atau .xls (Max: 10MB)</p>
                </label>

                @if ($file)
                <div class="mt-6">
                    <div
                        class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 inline-flex items-center gap-3">
                        <svg class="w-10 h-10 text-green-600 dark:text-green-500" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="text-left">
                            <p class="font-semibold text-zinc-800 dark:text-zinc-200">
                                {{ $file->getClientOriginalName() }}
                            </p>
                        </div>

                        <button type="button" wire:click="$set('file', null)"
                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 ml-4 transition-colors">
                            ✕
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="flex-1 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center gap-2">
                    Import Data Karyawan
                </button>

                <a href="{{ url()->previous() }}"
                    class="bg-zinc-500 hover:bg-zinc-600 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center gap-2">
                    Kembali
                </a>
            </div>

        </form>

    </div>

    <hr class="my-8 border-zinc-300 dark:border-zinc-700">


</div>