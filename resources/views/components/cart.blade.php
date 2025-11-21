@props(['producto'])



<div
    class="
        max-w-sm  
        cursor-pointer   
        rounded-2xl   
        overflow-hidden 
        hover:shadow-xl 
        transition 
        transform 
        hover:-translate-y-1
        bg-white
    ">

    <div class="relative h-70  ">

        @if (  count($producto['product_imagens']) > 0)
            @php
            
                $cleanPath = str_replace('public/', '', $producto['product_imagens'][0]['path']);
            @endphp
            <img src="/storage/{{ $cleanPath }}" alt="{{ $producto->mainImage ?? '' }}" class="w-full h-full object-cover">
        @else
            <div class="h-full flex items-center justify-center text-gray-400 text-sm">

            </div>
        @endif
        <!-- Badge -->
        @if (($producto['stock'] ?? $producto->stock) > 0)
            <span class="absolute top-2 right-2  text-black text-xs font-semibold px-2 py-1 rounded-full">
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
            <h2 class="text-lg font-bold text-black truncate text-titulo-1">
                {{ $producto['name'] ?? $producto->name }}
            </h2>
            <span class="  text-generico text-sky-500    ">
                ${{ number_format($producto['price'] ?? $producto->price, 2) }}
            </span>


        </div>
    </div>
</div>
