@props([
    'searchPlaceholder' => 'Buscar...',
    'showSearch' => true,
    'showPerPage' => true,
    'perPageOptions' => [5, 10, 20, 50],
])

<div class="space-y-4">
    {{-- Primera fila --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Búsqueda --}}
        @if($showSearch)
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </span>
            </label>
            <div class="relative">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        {{-- Slot para filtros personalizados --}}
        {{ $slot }}

        {{-- Por página --}}
        @if($showPerPage)
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
                @foreach($perPageOptions as $option)
                    <option value="{{ $option }}">{{ $option }} registros</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    {{-- Segunda fila (para filtros adicionales) --}}
    @if(isset($additionalFilters))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{ $additionalFilters }}
        </div>
    @endif
</div>