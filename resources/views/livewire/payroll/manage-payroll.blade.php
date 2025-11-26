<div>
    <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="import" class="flex flex-col gap-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Import Payroll Data') }}
            </h3>

            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div>
                <flux:input type="file" name="file" wire:model="file" :label="__('Excel File (.xlsx, .xls)')" />
                @error('file') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:target="file,import">{{ __('Import') }}</flux:button>
            </div>
        </form>
    </div>
</div>
