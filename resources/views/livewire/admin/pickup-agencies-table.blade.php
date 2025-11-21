<div class="space-y-6">
    {{-- Mensajes Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card title="Total Agencias" :value="$stats['total']" icon="building" color="blue" />

        <x-stat-card title="Activas" :value="$stats['active']" icon="check" color="green" />

        <x-stat-card title="Inactivas" :value="$stats['inactive']" icon="x" color="red" />
    </div>

    {{-- Tabla --}}
    <x-data-table :headers="[
        ['label' => 'ID', 'field' => 'id', 'sortable' => true],
        ['label' => 'Nombre', 'field' => 'name', 'sortable' => true],
        ['label' => 'Dirección', 'field' => 'address', 'sortable' => false],
        
        ['label' => 'Estado', 'field' => 'is_active', 'sortable' => true],
        ['label' => 'Fecha Creación', 'field' => 'created_at', 'sortable' => true],
        ['label' => 'Acciones', 'field' => 'actions', 'sortable' => false],
    ]" :rows="$agencies" empty-message="No se encontraron agencias">
        {{-- Header --}}
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">Agencias de Retiro</h2>
                <div class="flex space-x-2">
                    <button wire:click="openImportModal"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Importar Excel
                    </button>
                    <button wire:click="openCreateModal"
                        class="px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors flex items-center text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Agencia
                    </button>
                </div>
            </div>
        </x-slot>

        {{-- Filtros --}}
        <x-slot name="filters">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Búsqueda --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nombre, dirección..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filtro por Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select wire:model.live="statusFilter"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white">
                        <option value="">Todos</option>
                        <option value="1">✅ Activas</option>
                        <option value="0">❌ Inactivas</option>
                    </select>
                </div>

                {{-- Items por página --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Por Página</label>
                    <select wire:model.live="perPage"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white">
                        <option value="5">5 registros</option>
                        <option value="10">10 registros</option>
                        <option value="20">20 registros</option>
                        <option value="50">50 registros</option>
                    </select>
                </div>
            </div>
        </x-slot>

        {{-- Contenido de cada fila --}}
        @foreach ($agencies as $agency)
            <tr class="hover:bg-indigo-50 transition-colors duration-150">
                {{-- ID --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    #{{ $agency->id }}
                </td>

                {{-- Nombre --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $agency->name }}</div>
                        </div>
                    </div>
                </td>

                {{-- Dirección --}}
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ Str::limit($agency->address, 50) }}</div>
                </td>
 

                {{-- Estado --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($agency->is_active)
                        <span
                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            ✅ Activa
                        </span>
                    @else
                        <span
                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            ❌ Inactiva
                        </span>
                    @endif
                </td>

                {{-- Fecha --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>{{ $agency->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-400">{{ $agency->created_at->format('H:i') }}</div>
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button wire:click="openEditModal({{ $agency->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                        title="Editar">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </button>

                    <button wire:click="toggleStatus({{ $agency->id }})"
                        wire:confirm="¿Estás seguro de {{ $agency->is_active ? 'desactivar' : 'activar' }} esta agencia?"
                        class="inline-flex items-center px-3 py-1.5 {{ $agency->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors duration-200"
                        title="{{ $agency->is_active ? 'Desactivar' : 'Activar' }}">
                        @if ($agency->is_active)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Desactivar
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Activar
                        @endif
                    </button>

                    <button wire:click="deleteAgency({{ $agency->id }})"
                        wire:confirm="¿Estás seguro de eliminar esta agencia?"
                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200"
                        title="Eliminar">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </td>
            </tr>
        @endforeach

        {{-- Paginación --}}
        <x-slot name="pagination">
            {{ $agencies->links() }}
        </x-slot>
    </x-data-table>

    {{-- Modal Crear --}}
    @include('livewire.admin.agenct-modals.create-agency')

    {{-- Modal Editar --}}
    @include('livewire.admin.agenct-modals.edit-agency')

    {{-- Modal Importar --}}
    @include('livewire.admin.agenct-modals.import-agencies')
</div>
