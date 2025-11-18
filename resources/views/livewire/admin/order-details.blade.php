<div>
    @if($showModal && $order)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                {{-- Modal --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-white">Orden #{{ $order->order_number }}</h3>
                                <p class="text-indigo-100 text-sm mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <button wire:click="closeModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                            
                            {{-- Información de la Orden --}}
                            <div class="lg:col-span-2 space-y-6">
                                
                                {{-- Estado y Resumen --}}
                                <div class="bg-white rounded-lg border border-gray-200 p-6">
                                    <div class="flex justify-between items-start mb-6">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Estado de la Orden</h2>
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
                                            @endswitch
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Total</p>
                                            <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                    </div>

                                    {{-- Línea de tiempo del estado --}}
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

                                {{-- Información del Cliente --}}
                                <div class="bg-white rounded-lg border border-gray-200 p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Información del Cliente</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Nombre</p>
                                            <p class="text-gray-900 mt-1">{{ $order->user->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Email</p>
                                            <p class="text-gray-900 mt-1">{{ $order->user->email ?? 'N/A' }}</p>
                                        </div>
                                        @if($order->phone_number)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Teléfono</p>
                                            <p class="text-gray-900 mt-1">{{ $order->phone_number }}</p>
                                        </div>
                                        @endif
                                        @if($order->reference_number)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Referencia</p>
                                            <p class="text-gray-900 mt-1">{{ $order->reference_number }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Productos --}}
                                <div class="bg-white rounded-lg border border-gray-200 p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Productos</h2>
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
                                                    <h3 class="text-base font-medium text-gray-900">{{ $item->product->name ?? $item->product_name }}</h3>
                                                    @if($item->product && $item->product->description)
                                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($item->product->description, 60) }}</p>
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

                                    {{-- Totales --}}
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

                                {{-- Información de Envío y Pago --}}
                                <div class="bg-white rounded-lg border border-gray-200 p-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío y Pago</h2>
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

                                    {{-- Documentos --}}
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

                            {{-- Chat de Soporte --}}
                            <div class="lg:col-span-1">
                               @livewire('components.order-chat', ['orderId' => $order->id])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Script para auto-scroll del chat --}}
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('messageSent', () => {
                    setTimeout(() => {
                        const chatMessages = document.getElementById('chatMessages');
                        if (chatMessages) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    }, 100);
                });
            });
        </script>
    @endif
</div>