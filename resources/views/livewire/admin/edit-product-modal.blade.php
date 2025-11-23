<x-slide-over-modal 
    title="Editar Productosss" 
    wire:show="showModal" 
    wireClose="closeModal" 
    maxWidth="lg"
>
    <form id="edit-product-form" wire:submit.prevent="updateProduct" class="space-y-6 pb-20">
        
        {{-- SECCIÓN DE INFORMACIÓN BÁSICA --}}
        <h3 class="text-lg font-semibold border-b pb-2">Detalles del Producto</h3>

        <div class="grid grid-cols-2 gap-4">
            {{-- Campo Nombre --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Nombre del Producto"
                    required
                />
                @error('name')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Campo Slug --}}
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL Amigable)</label>
                <input 
                    type="text" 
                    id="slug"
                    wire:model="slug" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="ej: mi-producto"
                    required
                />
                @error('slug')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Campo Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea 
                id="description"
                wire:model="description" 
                rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
            @error('description')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            {{-- Campo Precio --}}
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Precio ($)</label>
                <input 
                    type="number" 
                    id="price"
                    step="0.01" 
                    wire:model="price" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="9.99"
                    required
                />
                @error('price')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Campo Stock --}}
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                <input 
                    type="number" 
                    id="stock"
                    wire:model="stock" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="100"
                    required
                />
                @error('stock')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Checkbox Activo --}}
            <div class="relative flex items-end pb-2">
                <div class="flex items-center h-5">
                    <input 
                        type="checkbox" 
                        id="is_active"
                        wire:model="is_active"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    />
                </div>
                <div class="ml-3 text-sm">
                    <label for="is_active" class="font-medium text-gray-700">Producto Activo</label>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE IMÁGENES --}}
        <h3 class="text-lg font-semibold border-b pt-4 pb-2">Imágenes</h3>

        {{-- IMAGEN ACTUAL --}}
        @if($product && $product->product_imagens->count() > 0)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual</label>
            <div class="relative inline-block">
                @php
                    $cleanPath = str_replace('public/', '', $product->product_imagens[0]->path);
                @endphp
                <img 
                    src="/storage/{{ $cleanPath }}" 
                    alt="Imagen actual" 
                    class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200"
                />
                <button 
                    type="button"
                    wire:click="deleteImage"
                    wire:confirm="¿Estás seguro de eliminar esta imagen?"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-lg"
                    title="Eliminar imagen"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Sube una nueva imagen para reemplazarla</p>
        </div>
        @endif

        {{-- NUEVA IMAGEN --}}
        <div>
            <label for="imagen" class="block text-sm font-medium text-gray-700">
                {{ $product && $product->product_imagens->count() > 0 ? 'Nueva Imagen (opcional)' : 'Imagen del Producto' }}
            </label>
            <input 
                type="file" 
                id="imagen"
                wire:model="imagen" 
                accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100"
            />
            @error('imagen')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
            
            <p class="mt-1 text-sm text-gray-500">PNG, JPG - Máximo 2MB</p>
            
            {{-- Preview de nueva imagen --}}
            @if ($imagen)
                <div class="mt-3">
                    <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                    <img src="{{ $imagen->temporaryUrl() }}" 
                         class="w-32 h-32 object-cover rounded-lg border-2 border-indigo-200"
                         alt="Vista previa"
                    />
                </div>
            @endif
            
        </div>

    </form>

    {{-- SLOT FOOTER --}}
    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <button 
                wire:click="closeModal" 
                type="button"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Cancelar
            </button>
            <button 
                type="submit"
                form="edit-product-form"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50" 
            >
                <span  >Actualizar Producto</span>
                
            </button>
        </div>
    </x-slot>

</x-slide-over-modal>