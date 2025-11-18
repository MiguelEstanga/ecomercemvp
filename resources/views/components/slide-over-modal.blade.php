@props(['title' => 'Formulario', 'show' => 'false', 'maxWidth' => 'md', 'wireClose'])

<div x-data="{ open: @entangle($attributes->wire('show')).live }" class="relative z-50">

    <div x-show="open" 
         x-transition:enter="ease-in-out duration-500" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-500" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
    </div>

    <div x-show="open" class="fixed inset-y-0 right-0 max-w-full flex">
        
        <div x-show="open" 
             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
             x-transition:enter-start="translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="translate-x-full" 
             class="w-screen max-w-{{ $maxWidth }}">
            
            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-auto">
                
                <div class="px-4 py-6 sm:px-6 bg-indigo-600 text-white flex justify-between items-start">
                    <h2 class="text-lg font-medium">{{ $title }}</h2>
                    <button {{ $attributes->wire('wireClose') }} type="button" class="ml-3 text-indigo-200 hover:text-white focus:outline-none">
                        <span class="sr-only">Cerrar panel</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                    {{ $slot }}
                </div>
                
                <div class="sticky bottom-0 left-0 right-0 px-4 py-4 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $footer }}
                </div>
            </div>
        </div>
    </div>
</div>