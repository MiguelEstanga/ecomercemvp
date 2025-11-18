@props([
    'headers' => [],
    'emptyMessage' => 'No se encontraron registros',
    'filters' => null,
    'rows' => null,
])

<div class="bg-white shadow-xl rounded-xl overflow-hidden">
    
    {{-- Header (si se proporciona slot) --}}
    @if(isset($header))
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
            {{ $header }}
        </div>
    @endif

    {{-- Filtros (si se proporciona slot) --}}
    @if(isset($filters))
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            {{ $filters }}
        </div>
    @endif

    {{-- Tabla --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            {{-- Headers --}}
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th 
                            @if(isset($header['sortable']) && $header['sortable'])
                                wire:click="sortBy('{{ $header['field'] }}')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            @else
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            @endif
                        >
                            <div class="flex items-center space-x-1">
                                <span>{{ $header['label'] }}</span>
                                
                                @if(isset($header['sortable']) && $header['sortable'])
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 10l5-5 5 5H5z"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            {{-- Body --}}
            <tbody class="bg-white divide-y divide-gray-200">
                @if(isset($rows) && $rows->count() > 0)
                    {{ $slot }}
                @else
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-4 text-gray-500 font-medium">{{ $emptyMessage }}</p>
                            <p class="text-sm text-gray-400">No hay datos para mostrar</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Paginación (si se proporciona) --}}
    @if(isset($pagination))
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $pagination }}
        </div>
    @endif
</div>