<div>
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreateModal">
                </div>

                {{-- Modal --}}
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white">Nueva Agencia de Retiro</h3>
                            <button wire:click="closeCreateModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <form wire:submit.prevent="createAgency">
                        <div class="p-6 space-y-4">

                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de la Agencia <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                    placeholder="Ej: Agencia Central" />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dirección --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                                    placeholder="Ej: Calle Principal 123, Colonia Centro, Ciudad"></textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_active" id="is_active_create"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                                <label for="is_active_create" class="ml-2 text-sm text-gray-700">
                                    Agencia activa
                                </label>
                            </div>

                            {{-- Nota informativa --}}
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Las agencias activas estarán disponibles para que los clientes seleccionen
                                            al realizar sus pedidos.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" wire:click="closeCreateModal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center"
                                wire:loading.attr="disabled">

                                <span   wire:target="createAgency">Crear Agencia</span>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
