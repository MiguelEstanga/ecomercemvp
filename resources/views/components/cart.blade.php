@props(['producto'])

@php
    // Si el producto es un modelo y tiene imágenes
    $imagen = null;
    if (is_object($producto) && method_exists($producto, 'product_imagens')) {
        $imagen =
            $producto->product_imagens->where('is_main', true)->first()->path ??
            ($producto->product_imagens->first()->path ?? null);
    } elseif (is_array($producto) && isset($producto['image'])) {
        // Si pasamos un array de prueba con clave 'image'
        $imagen = $producto['image'];
    }
@endphp

<div
    class="max-w-sm  cursor-pointer   rounded-2xl   overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
    <!-- Imagen -->
    <div class="relative h-70 bg-gray-600">
        @if ($imagen)
            <img src="{{ asset($imagen) }}" alt="{{ $producto['name'] ?? $producto->name }}"
                class="w-full h-full object-cover">
        @else
            <div class="h-full flex items-center justify-center text-gray-400 text-sm">
                Sin Imagen
            </div>
        @endif

        <!-- Badge -->
        @if (($producto['stock'] ?? $producto->stock) > 0)
            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                En stock
            </span>
        @else
            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                Agotado
            </span>
        @endif
    </div>

    <!-- Contenido -->
    <div class="p-4 flex flex-col justify-between h-20">
        <div>
            <span class="text-xl font-bold text-white">
                ${{ number_format($producto['price'] ?? $producto->price, 2) }}
            </span>
            <h2 class="text-lg font-bold text-white truncate">
                {{ $producto['name'] ?? $producto->name }}
            </h2>

        </div>
    </div>
</div>
