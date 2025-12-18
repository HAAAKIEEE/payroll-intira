<div class="min-h-screen bg-gray-50 dark:bg-zinc-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Data Payroll</h1>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                        Upload file Excel untuk import data payroll karyawan
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg mt-0.5"></i>
                <p class="text-sm text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Import Errors -->
        @if (session()->has('errors_import'))
        <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-red-200 dark:border-red-800 overflow-hidden">
            <div class="bg-red-50 dark:bg-red-900/20 px-5 py-3 border-b border-red-200 dark:border-red-800">
                <h4 class="font-semibold text-red-900 dark:text-red-400 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-sm"></i>
                    Beberapa baris gagal diimport
                </h4>
            </div>
            <div class="p-5">
                <ul class="space-y-4">
                    @foreach (session('errors_import') as $err)
                    <li class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-start gap-3 mb-2">
                            <span class="flex-shrink-0 w-6 h-6 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-xs font-bold">
                                {{ $err['row'] }}
                            </span>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-zinc-100">Baris: {{ $err['row'] }}</p>
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $err['reason'] }}</p>
                            </div>
                        </div>
                        
                        <details class="group mt-3">
                            <summary class="cursor-pointer text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium list-none flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs transform group-open:rotate-90 transition-transform"></i>
                                Lihat Detail Data
                            </summary>
                            <pre class="text-xs bg-gray-100 dark:bg-zinc-800 p-3 rounded-lg mt-2 overflow-x-auto text-gray-700 dark:text-zinc-300">{{ json_encode($err['data'], JSON_PRETTY_PRINT) }}</pre>
                        </details>
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
                                <span>Isi data payroll sesuai format template</span>
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
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-download text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Template Excel</h3>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Format standar import payroll</p>
                        </div>
                    </div>
                    <button wire:click="downloadTemplateImportPayroll"
                            class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-file-download"></i>
                        Download Template
                    </button>
                </div>

            </div>

            <!-- Right Column - Upload Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                    
                    <form wire:submit.prevent="import" enctype="multipart/form-data" id="importForm">
                        
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
                                       wire:model.defer="periode" 
                                       required
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
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
                                class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-12 text-center cursor-pointer transition-all duration-200 hover:border-green-400 hover:bg-green-50/30 dark:hover:bg-green-900/10">

                                <input type="file" 
                                       wire:model="file" 
                                       id="fileInput" 
                                       accept=".xlsx,.xls" 
                                       class="hidden">

                                <label for="fileInput" class="cursor-pointer block">
                                    @if (!$file)
                                    <div id="uploadPrompt">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 dark:text-zinc-500"></i>
                                        </div>
                                        <p class="text-gray-700 dark:text-zinc-300 mb-1">
                                            <span class="text-green-600 dark:text-green-400 font-semibold">Klik untuk upload</span> atau drag & drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-500">File Excel (.xlsx, .xls) - Max 10 MB</p>
                                    </div>
                                    @endif
                                </label>

                                @if ($file)
                                <div id="filePreview">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 p-5 rounded-xl">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white dark:bg-zinc-800 rounded-lg flex items-center justify-center shadow-sm">
                                                <i class="fas fa-file-excel text-green-600 dark:text-green-400 text-2xl"></i>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <p class="font-semibold text-gray-900 dark:text-zinc-100 text-sm">
                                                    {{ $file->getClientOriginalName() }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                                    {{ number_format($file->getSize() / 1024, 2) }} KB
                                                </p>
                                            </div>
                                            <button type="button" 
                                                    wire:click="$set('file', null)"
                                                    class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <i class="fas fa-times mr-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button type="submit"
                                    class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-green-400 disabled:cursor-not-allowed text-white px-5 py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-upload"></i>
                                <span>Import Data Payroll</span>
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
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('download-payroll-template', () => {
            window.location.href = '/template/payroll-fo.xlsx';
        });
    });

    // Drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');

        if (dropZone && fileInput) {
            ['dragenter', 'dragover'].forEach(evt =>
                dropZone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropZone.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                })
            );

            ['dragleave', 'drop'].forEach(evt =>
                dropZone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropZone.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                })
            );

            dropZone.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    fileInput.files = files;
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
        }
    });
</script>