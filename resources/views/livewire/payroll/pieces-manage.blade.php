<div>
    <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="import" class="flex flex-col gap-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Import Potongan Payroll Data') }}
            </h3>
            
            <!-- Success Message -->
            @if (session()->has('message'))
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
            @endif
            
            <!-- Error Message -->
            @if (session()->has('error'))
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                role="alert">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Detailed Errors -->
            @if (session()->has('errors_detail'))
            <div class="p-4 text-xs text-red-800 bg-red-50 rounded-lg dark:bg-red-900 dark:text-red-200 max-h-96 overflow-y-auto"
                role="alert">
                <div class="font-semibold mb-2">Detail Error:</div>
                <pre class="whitespace-pre-line font-mono">{{ session('errors_detail') }}</pre>
            </div>
            @endif
            
            <!-- Dropdown Periode -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Pilih Periode Payroll') }}
                    <span class="text-red-500">*</span>
                </label>
                <select wire:model.live="selectedPeriode" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periodes as $period)
                        <option value="{{ $period }}">{{ $period }}</option>
                    @endforeach
                </select>
                @error('selectedPeriode') 
                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span> 
                @enderror
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Excel File (.xlsx, .xls)') }}
                    <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       wire:model="file" 
                       accept=".xlsx,.xls"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                @error('file') 
                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span> 
                @enderror
                
                <!-- File info -->
                @if($file)
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    File dipilih: {{ $file->getClientOriginalName() }}
                </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="p-4 text-sm text-blue-700 bg-blue-50 rounded-lg dark:bg-blue-900 dark:text-blue-200">
                <div class="font-semibold mb-2">📋 Catatan Penting:</div>
                <ul class="list-disc list-inside space-y-1">
                    <li>NIK di Excel harus sama persis dengan NIK di database</li>
                    <li>User harus memiliki data UserBranche (cabang)</li>
                    <li>Data duplikat (kategori + keterangan sama) akan dilewati</li>
                    <li>Kolom NIK ada di kolom N (ke-14)</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4">
                <button type="submit" 
                        wire:loading.attr="disabled" 
                        wire:target="file,import"
                        class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="import">
                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        {{ __('Import Data') }}
                    </span>
                    <span wire:loading wire:target="import" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Processing...') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>