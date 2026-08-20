<x-filament-panels::page>
    <div x-data="{ loading: true }" class="relative w-full" style="height: calc(100vh - 170px);">
        <!-- Loading Spinner -->
        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-900 z-10 rounded-xl">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="mt-4 text-sm text-gray-500 dark:text-gray-400 font-medium">Memuat halaman...</span>
            </div>
        </div>

        <!-- The Iframe -->
        <iframe 
            src="/mikhmon/index.php?session=global&hotspot={{ $hotspot }}" 
            class="w-full h-full border-0 rounded-xl shadow-sm bg-white"
            @load="loading = false"
            style="border: none;"
        ></iframe>
    </div>
</x-filament-panels::page>
