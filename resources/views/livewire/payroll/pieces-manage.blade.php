<div class="min-h-screen bg-gray-50 dark:bg-zinc-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cut text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Potongan Payroll</h1>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                        Upload file Excel untuk import data potongan payroll
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg mt-0.5"></i>
                <p class="text-sm text-green-800 dark:text-green-300">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-lg mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Errors -->
        @if (session()->has('errors_detail'))
        <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-red-200 dark:border-red-800 overflow-hidden">
            <div class="bg-red-50 dark:bg-red-900/20 px-5 py-3 border-b border-red-200 dark:border-red-800">
                <h4 class="font-semibold text-red-900 dark:text-red-400 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                    Detail Error
                </h4>
            </div>
            <div class="p-5 max-h-96 overflow-y-auto">
                <pre class="text-xs text-red-800 dark:text-red-300 font-mono whitespace-pre-line">{{ session('errors_detail') }}</pre>
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
                            Catatan Penting
                        </h3>
                    </div>
                    <div class="p-5">
                        <ul class="space-y-3 text-sm text-gray-700 dark:text-zinc-300">
                            <li class="flex gap-3">
                                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"></i>
                                <span>NIK di Excel harus sama persis dengan NIK di database</span>
                            </li>
                            <li class="flex gap-3">
                                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"></i>
                                <span>User harus memiliki data UserBranche (cabang)</span>
                            </li>
                            <li class="flex gap-3">
                                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"></i>
                                <span>Data duplikat (kategori + keterangan sama) akan dilewati</span>
                            </li>
                            <li class="flex gap-3">
                                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"></i>
                                <span>Kolom NIK ada di kolom N (ke-14)</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Download Template Card -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-download text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Template Excel</h3>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Format standar import potongan</p>
                        </div>
                    </div>
                    <button wire:click="downloadTemplateImportPayroll"
                            class="w-full inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-file-download"></i>
                        Download Template
                    </button>
                </div>

            </div>

            <!-- Right Column - Upload Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                    
                    <form wire:submit.prevent="import" class="space-y-6">
                        
                        <!-- Pilih Periode -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Pilih Periode Payroll <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400 dark:text-zinc-500"></i>
                                </div>
                                <select wire:model.live="selectedPeriode"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors appearance-none cursor-pointer">
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach($periodes as $period)
                                        <option value="{{ $period }}">{{ $period }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 dark:text-zinc-500 text-sm"></i>
                                </div>
                            </div>
                            @error('selectedPeriode')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle text-xs"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Upload Section -->
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Upload File Excel</h3>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mb-4">File maksimal 10 MB dalam format .xlsx atau .xls</p>
                            
                            <!-- Drop Zone -->
                            <div id="dropZone"
                                class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-12 text-center cursor-pointer transition-all duration-200 hover:border-red-400 hover:bg-red-50/30 dark:hover:bg-red-900/10">

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
                                            <span class="text-red-600 dark:text-red-400 font-semibold">Klik untuk upload</span> atau drag & drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-500">File Excel (.xlsx, .xls) - Max 10 MB</p>
                                    </div>
                                    @endif
                                </label>

                                @if ($file)
                                <div id="filePreview">
                                    <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border border-red-200 dark:border-red-800 p-5 rounded-xl">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white dark:bg-zinc-800 rounded-lg flex items-center justify-center shadow-sm">
                                                <i class="fas fa-file-excel text-red-600 dark:text-red-400 text-2xl"></i>
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
                                    wire:loading.attr="disabled" 
                                    wire:target="file,import"
                                    class="flex-1 bg-red-600 hover:bg-red-700 disabled:bg-red-400 disabled:cursor-not-allowed text-white px-5 py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="import" class="flex items-center gap-2">
                                    <i class="fas fa-upload"></i>
                                    <span>Import Data Potongan</span>
                                </span>
                                <span wire:loading wire:target="import" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Sedang Memproses...</span>
                                </span>
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
        Livewire.on('download-piece-template', () => {
            window.location.href = '/template/potongan_template.xlsx';
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
                    dropZone.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                })
            );

            ['dragleave', 'drop'].forEach(evt =>
                dropZone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropZone.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
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