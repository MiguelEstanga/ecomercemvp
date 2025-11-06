@extends('layouts.app')
@section('title', $product->name)
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2 text-white-500">
                <li><a href=" " class="hover:text-blue-600">Inicio</a></li>
                <li>/</li>
                <li><a href=" " class="hover:text-blue-600">Productos</a></li>
                <li>/</li>
                <li class="text-white-800 font-semibold">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- Galería de Imágenes --}}
            <div class="space-y-4">
                {{-- Imagen Principal --}}
                <div class="bg-gray-100 rounded-lg overflow-hidden aspect-square">
                    @if (!empty($product->product_imagens) && count($product->product_imagens) > 0)
                        <img id="mainImage" src="{{ $product->product_imagens[0]->path ?? '' }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Miniaturas --}}
                @if (!empty($product->product_imagens) && count($product->product_imagens) > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach ($product->product_imagens as $index => $image)
                            <button onclick="changeImage('{{ $image->url }}')"
                                class="bg-gray-100 rounded-lg overflow-hidden aspect-square border-2 border-transparent hover:border-blue-500 transition-all">
                                <img src="{{ $image->path }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Información del Producto --}}
            <div class="space-y-6">

                {{-- Título y Estado --}}
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl  font-bold text-white">{{ $product->name }}</h1>
                        @if ($product->is_active)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Disponible
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                No Disponible
                            </span>
                        @endif
                    </div>

                    {{-- Rating (ejemplo estático) --}}
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">(23 reseñas)</span>
                    </div>
                </div>

                {{-- Precio --}}
                <div>
                    <p class="text-4xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>

                </div>

                {{-- Descripción --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h3>
                    <p class="text-white-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                {{-- Stock --}}
                <div class="bg-sky-500/75 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-white-700 font-medium">Stock disponible:</span>
                        <span
                            class="text-lg font-bold {{ $product->stock > 10 ? 'text-white-600' : ($product->stock > 0 ? 'text-orange-600' : 'text-red-600') }}">
                            {{ $product->stock }} unidades
                        </span>
                    </div>
                </div>

                {{-- Cantidad y Botón de Compra --}}
                <form action="{{ route('checkout.index', $product->id) }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-white-700 mb-2">Cantidad</label>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="decreaseQuantity()"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold w-10 h-10 rounded-lg transition-colors">
                                -
                            </button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                max="{{ $product->stock }}"
                                class="w-40 text-center border border-gray-300 rounded-lg py-2 font-semibold">
                            <button type="button" onclick="increaseQuantity({{ $product->stock }})"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold w-10 h-10 rounded-lg transition-colors">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex gap-3 mt-8">
                        @if ($product->is_active && $product->stock > 0)
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Comprar
                            </button>
                            <button type="button"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-4 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>
                        @else
                            <button type="button" disabled
                                class="flex-1 bg-gray-400 text-white font-bold py-4 rounded-lg cursor-not-allowed">
                                No Disponible
                            </button>
                        @endif
                    </div>
                </form>

                {{-- Información Adicional --}}
                <div class="border-t pt-6 space-y-3 text-sm text-white-600">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-white-700 font-medium">Envío gratis en pedidos mayores a $50</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Garantía de devolución de 30 días</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Entrega en 3-5 días hábiles</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección de Comentarios y Reseñas --}}
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Reseñas de Clientes</h2>

            {{-- Formulario de Comentario --}}
            @auth
                <div class=" dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Escribe tu reseña</h3>
                    <form action="{{ route('product.comment' , $product_id ) }}" method="POST">
                        @csrf

                        {{-- Rating --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-whitte mb-2">Calificación</label>
                            <div class="flex gap-2" id="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setRating({{ $i }})"
                                        class="text-gray-300 hover:text-yellow-400 transition-colors">
                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="5">
                        </div>

                        {{-- Comentario --}}
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-wrhitte mb-2">Tu comentario</label>
                            <textarea name="content" id="comment" rows="4" required
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Cuéntanos tu experiencia con este producto..."></textarea>
                        </div>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                            Publicar Reseña
                        </button>
                    </form>
                </div>
            @else
                <div class=" border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-white-800">
                        <a href="{{ route('login') }}" class="font-semibold hover:underline text-white">Inicia sesión</a>
                        para dejar una reseña
                    </p>
                </div>
            @endauth

            {{-- Lista de Comentarios --}}
            <div class="space-y-4">
                {{-- Ejemplo de comentario (repite esto con @foreach para comentarios reales) --}}
                @forelse($comment ?? [] as $review)
                    <div class="bg-gray-800 rounded-lg shadow-md p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $review->user->name ?? 'Usuario' }}</p>
                                    <div class="flex items-center gap-2">
                                        {{-- <div class="flex text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 5) ? 'fill-current' : 'fill-gray-300' }}"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                </svg>
                                            @endfor
                                        </div> --}}
                                        <span
                                            class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-whitte leading-relaxed">{{ $review->content }}</p>
                    </div>
                @empty
                    <div class="bg-gray-800 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-whitte mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                        <p class="text-whitte">Aún no hay reseñas para este producto</p>
                        <p class="text-sm text-whitte mt-1">¡Sé el primero en compartir tu opinión!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- JavaScript para funcionalidades interactivas --}}
    <script>
        // Cambiar imagen principal
        function changeImage(url) {
            document.getElementById('mainImage').src = url;
        }

        // Control de cantidad
        function increaseQuantity(max) {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        // Sistema de rating
        function setRating(rating) {
            document.getElementById('rating-input').value = rating;
            const stars = document.querySelectorAll('#rating-stars button svg');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // Inicializar rating en 5 estrellas
        document.addEventListener('DOMContentLoaded', function() {
            setRating(5);
        });
    </script>
@endsection
