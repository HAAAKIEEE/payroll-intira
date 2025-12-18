<x-layouts.app.sidebar>

    <flux:main class="min-h-screen bg-gray-50 dark:bg-zinc-950">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Payroll AM</h1>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                            Upload file Excel untuk import data payroll Area Manager
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg mt-0.5"></i>
                    <p class="text-sm text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-lg mt-0.5"></i>
                    <p class="text-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Import Errors -->
            @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-amber-200 dark:border-amber-800 overflow-hidden">
                <div class="bg-amber-50 dark:bg-amber-900/20 px-5 py-3 border-b border-amber-200 dark:border-amber-800">
                    <h4 class="font-semibold text-amber-900 dark:text-amber-400 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                        Beberapa baris gagal diimport
                    </h4>
                </div>
                <div class="p-5">
                    <ul class="space-y-3">
                        @foreach(session('import_errors') as $error)
                        <li class="flex items-start gap-3 text-sm">
                            <span class="flex-shrink-0 w-6 h-6 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center text-xs font-bold">
                                {{ $error['row'] ?? 'N/A' }}
                            </span>
                            <span class="text-gray-700 dark:text-zinc-300">
                                <span class="font-medium">Baris {{ $error['row'] ?? 'N/A' }}:</span> {{ $error['reason'] }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Main Grid Layout -->
            <div class="grid lg:grid-cols-3 gap-6">
                
                <!-- Left Column - Instructions & Template -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Instructions Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="bg-blue-50 dark:bg-blue-900/20 px-5 py-3 border-b border-blue-100 dark:border-blue-900">
                            <h3 class="font-semibold text-blue-900 dark:text-blue-300 flex items-center gap-2">
                                <i class="fas fa-info-circle text-sm"></i>
                                Petunjuk Import
                            </h3>
                        </div>
                        <div class="p-5">
                            <ol class="space-y-3 text-sm text-gray-700 dark:text-zinc-300">
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold">1</span>
                                    <span>Download template Excel terlebih dahulu</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold">2</span>
                                    <span>Pilih periode payroll yang sesuai</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold">3</span>
                                    <span>Isi data payroll AM sesuai format template</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold">4</span>
                                    <span>Upload file dan klik Import</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold">5</span>
                                    <span>Tunggu hingga proses import selesai</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Download Template Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-download text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Template Excel</h3>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">Format standar import payroll AM</p>
                            </div>
                        </div>
                        <a href="{{ route('download-template-payroll-am') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-file-download"></i>
                            Download Template
                        </a>
                    </div>

                </div>

                <!-- Right Column - Upload Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                        
                        <form action="{{ route('master-data.import-payroll-am') }}" 
                              method="POST" 
                              enctype="multipart/form-data"
                              id="importForm">
                            @csrf
                            
                            <!-- Periode Payroll -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    Periode Payroll <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400 dark:text-zinc-500"></i>
                                    </div>
                                    <input type="month" 
                                           name="periode"
                                           value="{{ old('periode', now()->format('Y-m')) }}"
                                           required
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                @error('periode')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle text-xs"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Upload Section -->
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Upload File Excel</h3>
                                <p class="text-sm text-gray-500 dark:text-zinc-400 mb-4">File maksimal 10 MB dalam format .xlsx atau .xls</p>
                                
                                <!-- Drop Zone -->
                                <div id="dropZone"
                                    class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-12 text-center cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/30 dark:hover:bg-blue-900/10">

                                    <input type="file" 
                                           name="file" 
                                           id="fileInput" 
                                           accept=".xlsx,.xls" 
                                           required
                                           class="hidden">

                                    <label for="fileInput" class="cursor-pointer block" id="uploadLabel">
                                        <div id="uploadPrompt">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 dark:text-zinc-500"></i>
                                            </div>
                                            <p class="text-gray-700 dark:text-zinc-300 mb-1">
                                                <span class="text-blue-600 dark:text-blue-400 font-semibold">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-zinc-500">File Excel (.xlsx, .xls) - Max 10 MB</p>
                                        </div>
                                    </label>

                                    <!-- File Preview -->
                                    <div id="filePreview" class="hidden">
                                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 p-5 rounded-xl">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-white dark:bg-zinc-800 rounded-lg flex items-center justify-center shadow-sm">
                                                    <i class="fas fa-file-excel text-blue-600 dark:text-blue-400 text-2xl"></i>
                                                </div>
                                                <div class="flex-1 text-left">
                                                    <p id="fileName" class="font-semibold text-gray-900 dark:text-zinc-100 text-sm"></p>
                                                    <p id="fileSize" class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5"></p>
                                                </div>
                                                <button type="button" 
                                                        id="removeFile"
                                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <i class="fas fa-times mr-1"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @error('file')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle text-xs"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button type="submit" 
                                        id="submitBtn"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white px-5 py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-upload"></i>
                                    <span id="btnText">Import Data Payroll</span>
                                </button>

                                <a href="{{ url()->previous() }}"
                                   class="px-6 py-3 rounded-lg border border-gray-300 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium transition-colors text-center">
                                   Kembali
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

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
            const uploadPrompt = document.getElementById("uploadPrompt");
            const dropZone = document.getElementById("dropZone");
            const submitBtn = document.getElementById("submitBtn");
            const btnText = document.getElementById("btnText");
            const importForm = document.getElementById("importForm");

            // File change handler
            fileInput.addEventListener("change", () => {
                const file = fileInput.files[0];
                if (!file) return;

                fileName.textContent = file.name;
                fileSize.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
                uploadPrompt.classList.add("hidden");
                filePreview.classList.remove("hidden");
            });

            // Remove file handler
            removeFile.addEventListener("click", () => {
                fileInput.value = "";
                filePreview.classList.add("hidden");
                uploadPrompt.classList.remove("hidden");
            });

            // Drag and drop handlers
            ['dragenter', 'dragover'].forEach(evt =>
                dropZone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                })
            );

            ['dragleave', 'drop'].forEach(evt =>
                dropZone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                })
            );

            dropZone.addEventListener("drop", (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });

            // Form submit handler
            importForm.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                btnText.innerHTML = 'Sedang Import...';
            });
        });
    </script>

</x-layouts.app.sidebar>