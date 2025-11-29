<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Branch & Region Management</h1>
        <p class="text-gray-600 text-sm mt-1">Manage branches and regions for your organization</p>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    {{-- Bento Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 auto-rows-max">
        
        {{-- Top Row --}}
        {{-- Quick Add Region (Top Left) --}}
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl shadow-sm border border-purple-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $isRegionEditMode ? 'Edit Region' : 'Quick Add Region' }}
                </h2>

                <form wire:submit.prevent="saveRegion">
                    <div class="mb-4">
                        <label for="region_name_quick" class="block text-sm font-medium text-gray-700 mb-2">
                            Region Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model.defer="region_name" 
                            id="region_name_quick" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition bg-white"
                            placeholder="Enter region name"
                        >
                        @error('region_name') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button 
                            wire:click.prevent="createRegion" 
                            type="button" 
                            class="flex-1 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition border border-gray-300"
                        >
                            New
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition"
                        >
                            {{ $isRegionEditMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Create Branch (Top Right) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 ">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $isBranchEditMode ? 'Edit Branch' : 'Create Branch' }}
                    </h2>
                    <a href="{{ route('master-data.import-branch.index') }}"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                        + Import Excel
                    </a>
                </div>

                <form wire:submit.prevent="saveBranch">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Branch Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Branch Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model.defer="name" 
                                id="name" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Enter branch name"
                            >
                            @error('name') 
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Region Selection or Create --}}
                        @if($createNewRegion)
                            <div class="p-3 border-2 border-blue-100 rounded-lg bg-blue-50/50">
                                <label for="region_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Region Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model.defer="region_name" 
                                    id="region_name" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                                    placeholder="Enter region name"
                                >
                                @error('region_name') 
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div>
                                <label for="region_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Region <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    wire:model.defer="region_id" 
                                    id="region_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                >
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">
                                            {{ $region->name }} ({{ $region->branches_count }} branches)
                                        </option>
                                    @endforeach
                                </select>
                                @error('region_id') 
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    {{-- Address --}}
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea 
                            wire:model.defer="address" 
                            id="address" 
                            rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter branch address"
                        ></textarea>
                        @error('address') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Region Toggle --}}
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                wire:model.live="createNewRegion" 
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                Create New Region
                            </span>
                        </label>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 mt-4">
                        <button 
                            wire:click.prevent="createBranch" 
                            type="button" 
                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition"
                        >
                            New
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition"
                        >
                            {{ $isBranchEditMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bottom Row --}}
        {{-- Branches List (Bottom Left) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Branches List</h2>
                    
                    {{-- Search --}}
                    <div class="w-64">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search branches..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                        >
                    </div>
                </div>

                {{-- Branches Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Branch Name</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Region</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Address</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $branch)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 text-sm text-gray-800 font-medium">{{ $branch->name }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $branch->region->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ $branch->address ?: '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center gap-2">
                                            <button 
                                                wire:click="editBranch({{ $branch->id }})" 
                                                class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium rounded-lg transition"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                wire:click="deleteBranch({{ $branch->id }})" 
                                                onclick="return confirm('Are you sure you want to delete this branch?')"
                                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">
                                        No branches found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $branches->links() }}
                </div>
            </div>
        </div>

        {{-- Regions List (Bottom Right) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 ">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Regions List</h2>
                </div>

                {{-- Search --}}
                <div class="mb-4">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="regionSearch" 
                        placeholder="Search regions..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm"
                    >
                </div>

                {{-- Regions List --}}
                <div class="space-y-3 overflow-y-auto max-h-96">
                    @forelse($regions as $region)
                        <div class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:shadow-sm transition group">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-800 group-hover:text-purple-600 transition">
                                        {{ $region->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $region->branches_count }} {{ Str::plural('branch', $region->branches_count) }}
                                    </p>
                                </div>
                                <div class="flex gap-1">
                                    <button 
                                        wire:click="editRegion({{ $region->id }})" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition"
                                        title="Edit"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        wire:click="deleteRegion({{ $region->id }})" 
                                        onclick="return confirm('Are you sure you want to delete this region?')"
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded transition"
                                        title="Delete"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-500">
                            No regions found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>