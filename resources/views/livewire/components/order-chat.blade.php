<div 
    class="bg-white rounded-lg border border-gray-200 h-full flex flex-col"
    x-data="{ 
        orderId: {{ $orderId }},
        connected: false 
    }"
    x-init="
        // Esperar a que Echo esté disponible
        const initEcho = () => {
            if (typeof window.Echo !== 'undefined') {
                console.log('🔥 Conectando al canal order.' + orderId);
                
                // Escuchar el canal de la orden
                window.Echo.channel('order.' + orderId)
                    .listen('.NewOrderMessage', (e) => {
                        console.log('📨 Nuevo mensaje recibido:', e);
                        // Llamar al método Livewire para recargar mensajes
                        @this.call('receiveMessage');
                    });
                
                connected = true;
                console.log('✅ Conectado al chat en tiempo real');
            } else {
                console.log('⏳ Esperando Echo...');
                setTimeout(initEcho, 100);
            }
        };
        
        initEcho();
    "
>
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
        <div class="flex items-center">
            <h2 class="text-lg font-semibold text-gray-900">Chat de Soporte</h2>
            @if($this->unreadCount > 0)
                <span class="ml-2 px-2 py-1 text-xs font-bold bg-red-500 text-white rounded-full animate-pulse">
                    {{ $this->unreadCount }}
                </span>
            @endif
            {{-- Indicador de conexión --}}
            <span 
                x-show="connected" 
                class="ml-3 flex items-center text-xs text-green-600"
            >
                <span class="h-2 w-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                Tiempo Real
            </span>
            <span 
                x-show="!connected" 
                class="ml-3 flex items-center text-xs text-gray-500"
            >
                <span class="h-2 w-2 bg-gray-400 rounded-full mr-1"></span>
                Conectando...
            </span>
        </div>
        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
            🚀 WebSocket
        </span>
    </div>
    
    {{-- Información del Cliente (solo para admin) --}}
    @if($userType === 'admin' && $order && $order->user)
    <div class="px-6 py-3 bg-indigo-50 border-b border-indigo-100">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-xs font-medium">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-indigo-900">{{ $order->user->name }}</p>
                <p class="text-xs text-indigo-700">{{ $order->user->email }}</p>
            </div>
        </div>
    </div>
    @endif
    
    {{-- Área de mensajes --}}
    <div 
        class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4" 
        id="chatMessages-{{ $orderId }}"
        style="max-height: 400px;"
    >
        @forelse($messages as $message)
            @php
                $isFromCurrentUser = $userType === 'admin' 
                    ? in_array($message['sender_type'], ['admin', 'system'])
                    : $message['sender_type'] === 'customer';
                
                $senderName = $message['user']['name'] ?? 'Sistema';
                $senderInitial = strtoupper(substr($senderName, 0, 1));
            @endphp

            @if($isFromCurrentUser)
                {{-- Mensaje del usuario actual --}}
                <div class="flex items-start justify-end">
                    <div class="mr-3 max-w-xs">
                        <div class="bg-indigo-600 rounded-lg px-4 py-2 shadow-sm">
                            <p class="text-sm text-white">{{ $message['message'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-right">
                            {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-medium">{{ $senderInitial }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Mensaje del otro usuario --}}
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $message['sender_type'] === 'system' ? 'bg-gray-400' : 'bg-green-600' }} rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-medium">{{ $senderInitial }}</span>
                        </div>
                    </div>
                    <div class="ml-3 max-w-xs">
                        <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                            @if($message['sender_type'] !== 'system' && $userType === 'admin')
                                <p class="text-xs text-gray-500 mb-1">{{ $senderName }}</p>
                            @endif
                            <p class="text-sm text-gray-900">{{ $message['message'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        @empty
            <div class="flex flex-col items-center justify-center h-full text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-gray-500 text-sm">No hay mensajes aún</p>
                <p class="text-gray-400 text-xs mt-2">
                    {{ $userType === 'admin' ? 'Inicia la conversación con el cliente' : 'Inicia la conversación con soporte' }}
                </p>
            </div>
        @endforelse
    </div>
    
    {{-- Indicador de escritura --}}
    {{-- <div wire:loading wire:target="sendMessage" class="px-4 py-2 bg-gray-50 border-t border-gray-200">
        <div class="flex items-center text-sm text-gray-500">
            <div class="flex space-x-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
            </div>
            <span class="ml-2">Enviando...</span>
        </div>
    </div> --}}
    
    {{-- Input de mensaje --}}
    <div class="p-4 border-t border-gray-200 bg-white">
        <form wire:submit.prevent="sendMessage" class="flex space-x-2">
            <input 
                type="text" 
                wire:model="newMessage"
                placeholder="Escribe un mensaje..." 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                maxlength="1000"
            />
            <button 
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
        
        @error('newMessage')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
        
        <div class="flex justify-between items-center mt-2">
            <p class="text-xs text-gray-400">
                <span class="text-green-600">●</span> Conectado en tiempo real
            </p>
            <p class="text-xs text-gray-400">{{ strlen($newMessage) }}/1000</p>
        </div>
    </div>
</div>

{{-- Script para auto-scroll del chat --}}
@script
<script>
    // Auto-scroll al enviar mensaje
    $wire.on('messageSent', () => {
        setTimeout(() => {
            const chatContainer = document.getElementById('chatMessages-{{ $orderId }}');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }, 100);
    });
    
    // Auto-scroll al cargar mensajes
    $wire.on('messagesLoaded', () => {
        setTimeout(() => {
            const chatContainer = document.getElementById('chatMessages-{{ $orderId }}');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }, 100);
    });
    
    // Auto-scroll inicial
    setTimeout(() => {
        const chatContainer = document.getElementById('chatMessages-{{ $orderId }}');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }, 200);
</script>
@endscript