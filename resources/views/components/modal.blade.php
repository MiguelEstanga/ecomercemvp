@props([
    'name',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'closeButton' => true,
    'backdrop' => false,
    'footerClass' => 'justify-end',
])

@php
    $sizeClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        'full' => 'max-w-full mx-4',
    ];
    $modalSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<!-- Modal Backdrop -->
<div id="{{ $name }}" 
    style="background-color: rgba(0, 0, 0, 0.5);"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     aria-labelledby="{{ $name }}-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    @if($backdrop)
    <div 

        class="fixed inset-0 bg-black   transition-opacity"
         onclick="closeModal('{{ $name }}')"></div>
    @endif
    
    <!-- Modal Container -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <!-- Modal Content -->
        <div class="{{ $modalSize }} w-full relative bg-white rounded-lg shadow-xl transform transition-all"
             {{ $attributes->merge(['class' => '']) }}>
            
            <!-- Header -->
            @if($title || $closeButton)
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                @if($title)
                <h3 class="text-xl font-semibold text-gray-900" id="{{ $name }}-title">
                    {{ $title }}
                </h3>
                @endif
                
                @if($closeButton)
                <button type="button" 
                        onclick="closeModal('{{ $name }}')"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                @endif
            </div>
            @endif
            
            <!-- Body -->
            <div class="p-6">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            @isset($footer)
            <div class="flex items-center gap-3 p-4 border-t border-gray-200 {{ $footerClass }}">
                {{ $footer }}
            </div>
            @endisset
        </div>
    </div>
</div>

<script>
    // Funciones globales para manejar modales
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
            modals.forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
</script>