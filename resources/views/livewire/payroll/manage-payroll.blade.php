<div>
    <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="import" class="flex flex-col gap-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Import Payroll Data') }}
            </h3>

            @if (session()->has('message'))
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('message') }}
            </div>
            @endif

            @if (session()->has('errors_import'))
            <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                <strong>Beberapa baris gagal diimport:</strong>
                <ul class="mt-2 list-disc ml-5">
                    @foreach (session('errors_import') as $err)
                    <li class="mb-2">
                        <strong>Baris:  {{ $err['row'] ?? 'Tidak diketahui' }}</strong><br>
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


            <div>
                <flux:input type="file" name="file" wire:model="file" :label="__('Excel File (.xlsx, .xls)')" />
                @error('file') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:target="file,import">{{
                    __('Import') }}</flux:button>
            </div>
        </form>
    </div>
</div>