@props(['maxWidth' => '2xl'])

<div 
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50" @click="show = false"></div>

    <!-- Modal Box -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-{{ $maxWidth }} z-50">
        
        <!-- Title -->
        <h2 class="text-lg font-semibold mb-4 dark:text-white">
            {{ $title }}
        </h2>

        <!-- Content -->
        <div class="mb-6">
            {{ $content }}
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2">
            {{ $footer }}
        </div>
    </div>
</div>
