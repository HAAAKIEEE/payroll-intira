<div>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Manage Regions') }}
        </h3>

        <!-- ADD BUTTON -->
        <button 
            wire:click="create"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
        >
            {{ __('Add Region') }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Branches') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($regions as $region)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium">{{ $region->name }}</td>
                            <td class="px-6 py-4">{{ $region->branches_count }}</td>
                            <td class="px-6 py-4 text-right">

                                <!-- EDIT BUTTON -->
                                <button 
                                    wire:click="edit({{ $region->id }})"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                                >
                                    Edit
                                </button>

                                <!-- DELETE BUTTON -->
                                <button 
                                    wire:click="delete({{ $region->id }})"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                >
                                    Delete
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center">No regions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $regions->links() }}
        </div>
    </div>

    <!-- MODAL -->
    <x-modal-dialog wire:model="showModal">
        <x-slot name="title">
            {{ $regionId ? 'Edit Region' : 'Add Region' }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col gap-4">
                <input 
                    type="text" 
                    wire:model="name"
                    class="w-full border-gray-300 dark:bg-gray-900 dark:border-gray-700 rounded-md"
                    placeholder="Region Name"
                >
                @error('name')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <button 
                wire:click="$set('showModal', false)"
                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400"
            >
                Cancel
            </button>

            <button 
                wire:click="save"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
                Save
            </button>
        </x-slot>
    </x-modal-dialog>
</div>
