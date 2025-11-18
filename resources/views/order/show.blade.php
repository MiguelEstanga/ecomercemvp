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
            <h1 class="text-3xl font-bold text-gray-900">Orden #{{ $order->order_number }}</h1>
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
                            @switch($order->status)
                                @case('pending')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        🟡 Pendiente
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        🔵 Procesando
                                    </span>
                                    @break
                                @case('shipped')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                        🟣 Enviado
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        🟢 Completado
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        🔴 Cancelado
                                    </span>
                                    @break
                                @default
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                            @endswitch
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>

                    <!-- Línea de tiempo del estado -->
                    <div class="relative pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">Pendiente</span>
                            <span class="text-xs font-medium text-gray-600">Procesando</span>
                            <span class="text-xs font-medium text-gray-600">Enviado</span>
                            <span class="text-xs font-medium text-gray-600">Completado</span>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                            <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500
                                @if($order->status === 'pending') w-1/4 bg-yellow-500
                                @elseif($order->status === 'processing') w-1/2 bg-blue-500
                                @elseif($order->status === 'shipped') w-3/4 bg-purple-500
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
                                @if($item->product && $item->product->product_imagens->count() > 0)
                                    @php
                                        $cleanPath = str_replace('public/', '', $item->product->product_imagens[0]->path);
                                    @endphp
                                    <img src="/storage/{{ $cleanPath }}" 
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
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name ?? $item->product_name }}</h3>
                                    @if($item->product && $item->product->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($item->product->description, 100) }}</p>
                                    @endif
                                    <div class="flex items-center mt-2">
                                        <span class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</span>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <span class="text-sm font-medium text-gray-900">${{ number_format($item->unit_price, 2) }} c/u</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totales -->
                    <div class="mt-6 pt-6 border-t space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($order->total_amount / 1.16, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">IVA (16%)</span>
                            <span class="text-gray-900">${{ number_format($order->total_amount - ($order->total_amount / 1.16), 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span class="text-gray-900">Total</span>
                            <span class="text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información de Envío y Pago -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información de Envío y Pago</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Dirección de Envío</p>
                            <p class="text-gray-900">{{ $order->shipping_address ?? 'No especificada' }}</p>
                            
                            @if($order->pickupAgency)
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm font-medium text-blue-900">Agencia de Retiro</p>
                                    <p class="text-sm text-blue-700 mt-1">{{ $order->pickupAgency->name }}</p>
                                    <p class="text-xs text-blue-600 mt-1">{{ $order->pickupAgency->address }}</p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Método de Pago</p>
                            <p class="text-gray-900">{{ $order->paymentMethod->name ?? 'N/A' }}</p>
                            
                            @if($order->observaciones)
                                <div class="mt-3">
                                    <p class="text-sm font-medium text-gray-500">Observaciones</p>
                                    <p class="text-sm text-gray-700 mt-1">{{ $order->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Documentos (si el usuario los subió) --}}
                    @if($order->imagen_documento || $order->imagen_comprobante)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm font-medium text-gray-500 mb-3">Documentos Adjuntos</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($order->imagen_documento)
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Documento</p>
                                    <a href="/storage/{{ $order->imagen_documento }}" target="_blank" class="block">
                                        <img src="/storage/{{ $order->imagen_documento }}" 
                                             alt="Documento" 
                                             class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-indigo-500 transition-colors">
                                    </a>
                                </div>
                            @endif
                            @if($order->imagen_comprobante)
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Comprobante de Pago</p>
                                    <a href="/storage/{{ $order->imagen_comprobante }}" target="_blank" class="block">
                                        <img src="/storage/{{ $order->imagen_comprobante }}" 
                                             alt="Comprobante" 
                                             class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-indigo-500 transition-colors">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Chat de Soporte - NUEVO CON WEBSOCKET -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <livewire:components.order-chat 
                        :orderId="$order->id" 
                        userType="customer"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script adicional si necesitas hacer algo más --}}
    <script>
        console.log('Vista de orden del cliente cargada');
    </script>
@endpush