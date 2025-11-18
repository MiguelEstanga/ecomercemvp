<div>
    @if($showModal && $user)
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
                                <h3 class="text-xl font-bold text-white">Órdenes de {{ $user->name }}</h3>
                                <p class="text-indigo-100 text-sm mt-1">{{ $user->email }}</p>
                            </div>
                            <button wire:click="closeModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6">
                        
                        {{-- Estadísticas --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Total Órdenes</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Completadas</p>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Pendientes</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Total Gastado</p>
                                <p class="text-2xl font-bold text-indigo-600">${{ number_format($stats['total_amount'], 2) }}</p>
                            </div>
                        </div>

                        {{-- Filtros --}}
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                    <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        <option value="">Todos</option>
                                        <option value="pending">Pendiente</option>
                                        <option value="processing">Procesando</option>
                                        <option value="shipped">Enviado</option>
                                        <option value="completed">Completado</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                                    <input wire:model.live="dateFrom" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                                    <input wire:model.live="dateTo" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>

                                <div class="flex items-end">
                                    <button wire:click="clearFilters" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                        Limpiar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla de Órdenes --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Orden</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($orders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-semibold text-indigo-600">{{ $order->order_number }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ $order->items->count() }} producto(s)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">🟡 Pendiente</span>
                                                        @break
                                                    @case('processing')
                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">🔵 Procesando</span>
                                                        @break
                                                    @case('shipped')
                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">🟣 Enviado</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">🟢 Completado</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🔴 Cancelado</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <button 
                                                    wire:click="viewOrder({{ $order->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 font-medium"
                                                >
                                                    Ver Detalles
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                No se encontraron órdenes
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginación --}}
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>