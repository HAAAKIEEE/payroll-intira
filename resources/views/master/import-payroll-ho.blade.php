<x-layouts.app.sidebar>

    <flux:main class="p-6">

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 p-8 space-y-8">

            <div class="flex items-center gap-3">
                <svg class="w-7 h-7 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Import Payroll AM</h2>
            </div>

            <form wire:submit.prevent="import" enctype="multipart/form-data" id="importForm" class="space-y-8">

                {{-- Dropzone --}}
                <div id="dropZone"
                    class="border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl p-10 text-center cursor-pointer transition-all duration-300 hover:border-blue-500 dark:hover:border-blue-500">

                    <input type="file" wire:model="file" id="fileInput" accept=".xlsx,.xls" class="hidden">

                    <label for="fileInput" class="block" id="uploadLabel">
                        <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 inline-flex items-center gap-4 animate-fadeIn">

                            <svg class="w-10 h-10 text-green-600 dark:text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" id="submitBtn" wire:loading.attr="disabled"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>
                            Import Data Karyawan
                        </span>
                        <span wire:loading>
                            ⏳ Sedang memproses...
                        </span>
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
});
</script>

</x-layouts.app.sidebar>
