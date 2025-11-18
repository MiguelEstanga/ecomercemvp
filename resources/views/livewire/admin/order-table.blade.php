<div class="space-y-6">
    {{-- Mensajes Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total Órdenes" 
            :value="$stats['total'] ?? 0" 
            icon="cart" 
            color="blue"
        />
        
        <x-stat-card 
            title="Pendientes" 
            :value="$stats['pending'] ?? 0" 
            icon="clock" 
            color="yellow"
        />
        
        <x-stat-card 
            title="Completadas" 
            :value="$stats['completed'] ?? 0" 
            icon="check" 
            color="green"
        />
        
        <x-stat-card 
            title="Total Ventas" 
            :value="'$' . number_format($stats['total_sales'] ?? 0, 2)" 
            icon="dollar" 
            color="indigo"
        />
    </div>

    {{-- Tabla con componente --}}
    <x-data-table 
        :headers="[
            ['label' => 'ID', 'field' => 'id', 'sortable' => true],
            ['label' => 'N° Orden', 'field' => 'order_number', 'sortable' => true],
            ['label' => 'Cliente', 'field' => 'user', 'sortable' => false],
            ['label' => 'Productos', 'field' => 'items', 'sortable' => false],
            ['label' => 'Total', 'field' => 'total_amount', 'sortable' => true],
            ['label' => 'Estado', 'field' => 'status', 'sortable' => true],
            ['label' => 'Fecha', 'field' => 'created_at', 'sortable' => true],
            ['label' => 'Acciones', 'field' => 'actions', 'sortable' => false],
        ]"
        :rows="$orders"
        empty-message="No se encontraron órdenes"
    >
        {{-- Header --}}
        <x-slot name="header">
            <h2 class="text-xl font-bold text-white">Gestión de Órdenes</h2>
        </x-slot>

        {{-- Filtros --}}
        <x-slot name="filters">
            <div class="space-y-4">
                {{-- Primera fila de filtros --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Búsqueda --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Buscar Orden
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                wire:model.live.debounce.300ms="search" 
                                type="text" 
                                placeholder="N° orden, referencia, cliente..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filtro por Estado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Estado
                            </span>
                        </label>
                        <select 
                            wire:model.live="statusFilter" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white"
                        >
                            <option value="">Todos los estados</option>
                            <option value="pending">🟡 Pendiente</option>
                            <option value="processing">🔵 Procesando</option>
                            <option value="shipped">🟣 Enviado</option>
                            <option value="completed">🟢 Completado</option>
                            <option value="cancelled">🔴 Cancelado</option>
                        </select>
                    </div>

                    {{-- Items por página --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                Por Página
                            </span>
                        </label>
                        <select 
                            wire:model.live="perPage" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white"
                        >
                            <option value="5">5 registros</option>
                            <option value="10">10 registros</option>
                            <option value="20">20 registros</option>
                            <option value="50">50 registros</option>
                        </select>
                    </div>
                </div>

                {{-- Segunda fila de filtros --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Filtro por Método de Pago --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Método de Pago
                            </span>
                        </label>
                        <select 
                            wire:model.live="paymentMethodFilter" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white"
                        >
                            <option value="">Todos los métodos</option>
                            @if(isset($paymentMethods))
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Fecha Desde --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                        <input 
                            wire:model.live="dateFrom" 
                            type="date" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm"
                        />
                    </div>

                    {{-- Fecha Hasta --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                        <input 
                            wire:model.live="dateTo" 
                            type="date" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm"
                        />
                    </div>

                    {{-- Botón Limpiar --}}
                    <div class="flex items-end">
                        <button 
                            wire:click="clearFilters"
                            class="w-full px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium shadow-sm hover:shadow-md flex items-center justify-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </x-slot>

        {{-- Contenido de cada fila --}}
        @foreach($orders as $order)
            <tr class="hover:bg-indigo-50 transition-colors duration-150">
                {{-- ID --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    #{{ $order->id }}
                </td>
                
                {{-- Número de Orden --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-semibold text-indigo-600">{{ $order->order_number }}</span>
                    @if($order->reference_number)
                        <div class="text-xs text-gray-500">Ref: {{ $order->reference_number }}</div>
                    @endif
                </td>
                
                {{-- Cliente --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold">
                                    {{ $order->user ? substr($order->user->name, 0, 1) : 'N' }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $order->user->name ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->user->email ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </td>
                
                {{-- Productos --}}
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($order->items && $order->items->count() > 0)
                            <div class="space-y-1">
                                @foreach($order->items as $item)
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs">{{ $item->product->name ?? $item->product_name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">x{{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-gray-400">Sin productos</span>
                        @endif
                    </div>
                </td>
                
                {{-- Total --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                </td>
                
                {{-- Estado --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    @switch($order->status)
                        @case('pending')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                🟡 Pendiente
                            </span>
                            @break
                        @case('processing')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                🔵 Procesando
                            </span>
                            @break
                        @case('shipped')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                🟣 Enviado
                            </span>
                            @break
                        @case('completed')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                🟢 Completado
                            </span>
                            @break
                        @case('cancelled')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                🔴 Cancelado
                            </span>
                            @break
                        @default
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($order->status) }}
                            </span>
                    @endswitch
                </td>
                
                {{-- Fecha --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>{{ $order->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                </td>
                
                {{-- Acciones --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button 
                        wire:click="viewOrder({{ $order->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors duration-200"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </button>
                    
                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                    <div class="inline-block relative group">
                        <button class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Estado
                        </button>
                        <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 border border-gray-200">
                            <div class="py-1">
                                @if($order->status !== 'processing')
                                <button 
                                    wire:click="updateOrderStatus({{ $order->id }}, 'processing')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50"
                                >
                                    🔵 Procesando
                                </button>
                                @endif
                                @if($order->status !== 'shipped')
                                <button 
                                    wire:click="updateOrderStatus({{ $order->id }}, 'shipped')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50"
                                >
                                    🟣 Enviado
                                </button>
                                @endif
                                @if($order->status !== 'completed')
                                <button 
                                    wire:click="updateOrderStatus({{ $order->id }}, 'completed')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50"
                                >
                                    🟢 Completado
                                </button>
                                @endif
                                <button 
                                    wire:click="updateOrderStatus({{ $order->id }}, 'cancelled')"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 border-t"
                                >
                                    🔴 Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <button 
                        wire:click="deleteOrder({{ $order->id }})"
                        wire:confirm="¿Estás seguro de eliminar esta orden?"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </td>
            </tr>
        @endforeach

        {{-- Paginación --}}
        <x-slot name="pagination">
            {{ $orders->links() }}
        </x-slot>
    </x-data-table>
</div>