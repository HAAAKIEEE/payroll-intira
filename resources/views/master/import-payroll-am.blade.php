<x-layouts.app.sidebar>

    <flux:main class="p-6">
        <div
            class="flex items-center justify-between p-4 rounded-xl bg-white shadow-sm border dark:bg-zinc-800 dark:border-zinc-700">

            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/40">
                    <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-wide">
                    Download Template
                </h2>
            </div>

            {{-- <a href="{{ route('download-template') }}"
                class="flex items-center gap-2 px-5 py-2.5 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 rounded-lg transition">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                <span>Download</span>
            </a> --}}

        </div>

        {{-- Alert Success/Error --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Beberapa baris gagal diimport:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach(session('import_errors') as $error)
                <li>Baris {{ $error['row'] ?? 'N/A' }}: {{ $error['reason'] }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div
            class="bg-white dark:bg-zinc-800 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 p-8 space-y-8">
            <div class="flex items-center gap-3">
                <svg class="w-7 h-7 text-green-600 dark:text-green-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Import Payroll AM</h2>
            </div>

            <form action="{{ route('master-data.import-payroll-am') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
               {{-- Pilih Periode --}}
<div class="space-y-2">
    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
        Periode Payroll
    </label>

    <div
        class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 transition">
        
        <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
        </svg>

        <input
            type="month"
            name="periode"
            value="{{ old('periode', now()->format('Y-m')) }}"
            required
            class="w-full bg-transparent border-none focus:outline-none text-zinc-800 dark:text-zinc-100">
    </div>

    @error('periode')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>

                {{-- Dropzone --}}
                <div id="dropZone"
                    class="border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl p-10 text-center cursor-pointer transition-all duration-300 hover:border-blue-500 dark:hover:border-blue-500">

                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" class="hidden" required>

                    <label for="fileInput" class="block cursor-pointer" id="uploadLabel">
                        <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600 mx-auto mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>

                        <p class="text-lg font-medium text-zinc-700 dark:text-zinc-300">
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">Klik untuk upload</span>
                            atau drag & drop file Anda
                        </p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            Format: .xlsx atau .xls &nbsp; | &nbsp; Max: 10MB
                        </p>
                    </label>

                    {{-- Preview File --}}
                    <div id="filePreview" class="mt-8 hidden">
                        <div
                            class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 inline-flex items-center gap-4 animate-fadeIn">

                            <svg class="w-10 h-10 text-green-600 dark:text-green-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>

                            <div class="flex-1 min-w-0 text-left">
                                <p id="fileName" class="font-semibold text-zinc-800 dark:text-zinc-200 truncate"></p>
                                <p id="fileSize" class="text-sm text-zinc-500 dark:text-zinc-400 mt-1"></p>
                            </div>

                            <button type="button" id="removeFile"
                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1 transition rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @error('file')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

                {{-- Tombol --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" id="submitBtn"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        Import Data Payroll
                    </button>

                    <a href="{{ url()->previous() }}"
                        class="flex-1 bg-zinc-500 hover:bg-zinc-600 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 shadow-md">
                        Kembali
                    </a>
                </div>

            </form>
        </div>

    </flux:main>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const fileInput = document.getElementById("fileInput");
            const filePreview = document.getElementById("filePreview");
            const fileName = document.getElementById("fileName");
            const fileSize = document.getElementById("fileSize");
            const removeFile = document.getElementById("removeFile");
            const uploadLabel = document.getElementById("uploadLabel");
            const dropZone = document.getElementById("dropZone");

            // Saat file dipilih
            fileInput.addEventListener("change", () => {
                const file = fileInput.files[0];
                if (!file) return;

                fileName.textContent = file.name;
                fileSize.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
                filePreview.classList.remove("hidden");
                uploadLabel.classList.add("hidden");
            });

            // Hapus file
            removeFile.addEventListener("click", () => {
                fileInput.value = "";
                filePreview.classList.add("hidden");
                uploadLabel.classList.remove("hidden");
            });

            // Drag & Drop
            dropZone.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropZone.classList.add("border-blue-500");
            });

            dropZone.addEventListener("dragleave", () => {
                dropZone.classList.remove("border-blue-500");
            });

            dropZone.addEventListener("drop", (e) => {
                e.preventDefault();
                dropZone.classList.remove("border-blue-500");
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>

</x-layouts.app.sidebar>