@props([
    'type' => 'success',
    'message' => '',
])

@php
    $typeClasses = [
        'success' => 'bg-green-50 border-green-200 text-green-700',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
        'danger' => 'bg-red-50 border-red-200 text-red-700',
    ];
    $iconClasses = [
        'success' => 'h-5 w-5 text-green-400',
        'warning' => 'h-5 w-5 text-yellow-400',
        'danger' => 'h-5 w-5 text-red-400',
    ];
    $iconPath = [
        'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />',
        'warning' => '<path fill-rule="evenodd" d="M8.257 3.099c.983-1.745 3.32-1.745 4.303 0a1 1 0 011.516.425l.899 1.59a1 1 0 00.56.56l1.59.899c.772.434 1.258 1.246 1.258 2.148v4.296c0 .902-.486 1.714-1.258 2.148l-1.59.899a1 1 0 00-.56.56l-.899 1.59c-.434.772-1.246 1.258-2.148 1.258h-4.296c-.902 0-1.714-.486-2.148-1.258l-.899-1.59a1 1 0 00-.56-.56l-1.59-.899c-.772-.434-1.258-1.246-1.258-2.148v-4.296c0-.902.486-1.714 1.258-2.148l1.59-.899a1 1 0 00.56-.56l.899-1.59a1 1 0 011.516-.425z" clip-rule="evenodd" />',
        'danger' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />',
    ];
@endphp

<div @class(['rounded-lg mb-4 px-4 py-3', $typeClasses[$type]])>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg @class([$iconClasses[$type]]) xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                {!! $iconPath[$type] !!}
            </svg>
        </div>
        <div class="ml-3">
            {{ $message }}
        </div>
    </div>
</div>
