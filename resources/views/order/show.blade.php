@extends('layouts.app')
@section('title', 'Detalle de Orden')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="mb-6">
            <a href="{{ route('profile.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm font-medium mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Órdenes
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Orden #{{ $order->id }}</h1>
            <p class="text-gray-500 mt-1">Realizada el {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información de la Orden -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Estado y Resumen -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Estado de la Orden</h2>
                            <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    <!-- Línea de tiempo del estado -->
                    <div class="relative pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">Pendiente</span>
                            <span class="text-xs font-medium text-gray-600">Procesando</span>
                            <span class="text-xs font-medium text-gray-600">Completada</span>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                            <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center
                                @if($order->status === 'pending') w-1/3 bg-yellow-500
                                @elseif($order->status === 'processing') w-2/3 bg-blue-500
                                @elseif($order->status === 'completed') w-full bg-green-500
                                @endif">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Productos</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item->product->description }}</p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</span>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <span class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }} c/u</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totales -->
                    <div class="mt-6 pt-6 border-t space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($order->total / 1.16, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">IVA (16%)</span>
                            <span class="text-gray-900">${{ number_format($order->total - ($order->total / 1.16), 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span class="text-gray-900">Total</span>
                            <span class="text-gray-900">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información de Envío -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información de Envío</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Dirección</p>
                            <p class="text-gray-900 mt-1">{{ $order->shipping_address ?? 'No especificada' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Método de Pago</p>
                            <p class="text-gray-900 mt-1">{{ $order->payment_method ?? 'Tarjeta de crédito' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat de Soporte (Próximamente) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Chat de Soporte</h2>
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded">Próximamente</span>
                    </div>
                    
                    <!-- Área de mensajes -->
                    <div class="h-96 p-6 overflow-y-auto bg-gray-50">
                        <div class="flex flex-col items-center justify-center h-full text-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-gray-500 text-sm">El chat de soporte estará disponible próximamente</p>
                            <p class="text-gray-400 text-xs mt-2">Podrás comunicarte directamente con nuestro equipo</p>
                        </div>

                        <!-- Ejemplo de cómo se verán los mensajes -->
                        <div class="hidden">
                            <!-- Mensaje del soporte -->
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-medium">S</span>
                                        </div>
                                    </div>
                                    <div class="ml-3 max-w-xs">
                                        <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                            <p class="text-sm text-gray-900">¡Hola! ¿En qué puedo ayudarte con tu orden?</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">10:30 AM</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje del usuario -->
                            <div class="mb-4">
                                <div class="flex items-start justify-end">
                                    <div class="mr-3 max-w-xs">
                                        <div class="bg-indigo-600 rounded-lg px-4 py-2 shadow-sm">
                                            <p class="text-sm text-white">¿Cuándo llegará mi pedido?</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 text-right">10:31 AM</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-gray-700 text-xs font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Input de mensaje (deshabilitado) -->
                    <div class="p-4 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <input type="text" 
                                   placeholder="Escribe un mensaje..." 
                                   disabled
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm bg-gray-100 cursor-not-allowed">
                            <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">Función en desarrollo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection