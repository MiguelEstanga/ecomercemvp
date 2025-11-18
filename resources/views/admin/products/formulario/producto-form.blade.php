<x-slide-over-modal title="Crear Nuevo Producto" wire:show="showModal" wireClose="closeModal" maxWidth="lg">

    {{-- ⚠️ Nota: El form debe tener un ID para que el botón del footer funcione --}}
    <form enctype="multipart/form-data" id="livewire-form" action="{{ route('admin.productos.create') }}"
        class="space-y-6 pb-20" method="post">
        @csrf

        {{-- SECCIÓN DE INFORMACIÓN BÁSICA --}}
        <h3 class="text-lg font-semibold border-b pb-2">Detalles del Producto</h3>

        <div class="grid grid-cols-2 gap-4">
            {{-- Campo Nombre --}}
            <div>
                <x-text-input name="name" label="Nombre" placeholder="Nombre del Producto" required />
                @error('name')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Campo Slug --}}
            <div>
                <x-text-input name="slug" label="Slug (URL Amigable)" placeholder="ej: mi-nuevo-producto" required />
                @error('slug')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Campo Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
            @error('description')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            {{-- Campo Precio --}}
            <div>
                <x-text-input name="price" label="Precio ($)" type="number" step="0.01" placeholder="9.99"
                    required />
                @error('price')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            {{-- Campo Stock --}}
            <div>
                <x-text-input name="stock" label="Stock" type="number" placeholder="100" required />
                @error('stock')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Checkbox Activo --}}
            <div class="relative flex items-end pb-2">
                <div class="flex items-center h-5">
                    <input name="is_active" id="is_active" type="checkbox"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="is_active" class="font-medium text-gray-700">Producto Activo</label>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE IMÁGENES --}}
        <h3 class="text-lg font-semibold border-b pt-4 pb-2">Imágenes</h3>

        {{-- IMAGEN PRINCIPAL --}}
        <x-file-upload name="imagen" label="Imagen de perfil" accept="image/*" maxSize="5MB"
            allowedFormats="PNG, JPG" />




    </form>

    {{-- SLOT FOOTER --}}
    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <button wire:click.prevent="closeModal" type="button"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </button>
            <button form="livewire-form" type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                <span wire:loading.remove wire:target="saveProduct, main_image, image_2, image_3, image_4">Guardar
                    Producto</span>
                {{-- Muestra cargando si se suben archivos o se guarda el producto --}}
                <span wire:loading wire:target="saveProduct, main_image, image_2, image_3, image_4">Guardando...</span>
            </button>
        </div>
    </x-slot>

</x-slide-over-modal>


<script>
    // Validación antes de enviar
    document.getElementById('livewire-form').addEventListener('submit', function(e) {
        const documentImage = document.getElementById('imagen').files[0];


        if (!documentImage) {
            e.preventDefault();
            alert('Por favor, sube ambas imágenes requeridas.');
            return false;
        }

        // Deshabilitar botón para evitar doble submit
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').textContent = 'Procesando...';
    });
</script>
