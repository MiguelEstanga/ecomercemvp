@props([
    'name',
    'label',
    'required' => false,
    'accept' => 'image/*',
    'maxSize' => '5MB',
    'allowedFormats' => 'PNG, JPG',
    'defaultImage' => null, // Nueva propiedad para imagen por defecto
])

@php
    $uniqueId = Str::random(8);
    $areaId = $name . '_area_' . $uniqueId;
    $previewId = $name . '_preview_' . $uniqueId;
    $labelId = $name . '_label_' . $uniqueId;
    $hasDefaultImage = !empty($defaultImage);
@endphp

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    
    <div id="{{ $areaId }}"
        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition cursor-pointer @error($name) border-red-500 @enderror {{ $hasDefaultImage ? 'hidden' : '' }}">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path
                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600 justify-center">
                <label for="{{ $name }}"
                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                    <span id="{{ $labelId }}">Subir archivo</span>
                    <input 
                        id="{{ $name }}" 
                        name="{{ $name }}" 
                        type="file" 
                        accept="{{ $accept }}"
                        {{ $required && !$hasDefaultImage ? 'required' : '' }}
                        class="sr-only"
                        {{ $attributes }}>
                </label>
                <p class="pl-1">o arrastra y suelta</p>
            </div>
            <p class="text-xs text-gray-500">{{ $allowedFormats }} hasta {{ $maxSize }}</p>
        </div>
    </div>
    
    <!-- Preview de imagen -->
    <div id="{{ $previewId }}" class="mt-3 {{ $hasDefaultImage ? '' : 'hidden' }}">
        <img src="{{ $defaultImage }}" alt="Preview" class="max-w-full h-48 object-cover rounded-lg mx-auto">
        <button type="button" 
            onclick="removeFile{{ $uniqueId }}()"
            class="mt-2 text-sm text-red-600 hover:text-red-800 block mx-auto">
            ✕ Eliminar imagen
        </button>
    </div>
    
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>

<script>
    // Script inline para cada componente
    (function() {
        const input = document.getElementById('{{ $name }}');
        const areaId = '{{ $areaId }}';
        const previewId = '{{ $previewId }}';
        const labelId = '{{ $labelId }}';

        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validar tamaño (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 5MB.');
                    this.value = '';
                    return;
                }

                // Validar tipo
                if (!file.type.match('image.*')) {
                    alert('Por favor selecciona una imagen válida.');
                    this.value = '';
                    return;
                }

                // Mostrar nombre
                const labelElement = document.getElementById(labelId);
                if (labelElement) {
                    labelElement.textContent = file.name;
                }

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewElement = document.getElementById(previewId);
                    const areaElement = document.getElementById(areaId);
                    
                    if (previewElement) {
                        const img = previewElement.querySelector('img');
                        if (img) {
                            img.src = e.target.result;
                        }
                        previewElement.classList.remove('hidden');
                    }
                    
                    if (areaElement) {
                        areaElement.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // Función para eliminar archivo
        window.removeFile{{ $uniqueId }} = function() {
            if (input) input.value = '';
            
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.classList.add('hidden');
                const img = preview.querySelector('img');
                if (img) img.src = '';
            }
            
            const area = document.getElementById(areaId);
            if (area) area.classList.remove('hidden');
            
            const label = document.getElementById(labelId);
            if (label) label.textContent = 'Subir archivo';
        };
    })();
</script>