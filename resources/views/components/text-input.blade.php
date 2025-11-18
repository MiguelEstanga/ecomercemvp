@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
])
{{-- Eliminamos 'required' de los props definidos para que se transfiera si se usa. --}}

{{-- Determinamos si el atributo 'required' existe en la colección de atributos --}}
@php
    $isRequired = $attributes->has('required');
@endphp

<div class="mb-6">
    <label for="{{ $name }}" class="block text-sm font-medium mb-2 text-black">
        {{ $label }} @if($isRequired)<span class="text-red-500">*</span>@endif
    </label>
    <input 
        type="{{ $type }}"
        name="{{ $name }}" 
        id="{{ $name }}" {{-- Aseguramos el ID para accesibilidad --}}
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        
        {{-- Aquí transferimos todos los atributos (incluyendo wire:model, required, etc.) --}}
        {{ $attributes->merge([
            'class' => 'text-black w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ' . 
                       ($errors->has($name) ? 'border-red-500' : 'border-gray-300')
        ]) }}
    />
    
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>