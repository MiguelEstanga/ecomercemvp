@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'placeholder' => 'Seleccionar opción',
    'valueField' => 'id',
    'textField' => 'name',
])

<div class="mb-6">
    <label class="block text-black text-sm font-medium text-dark mb-2">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <select 
        name="{{ $name }}" 
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'text-black w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent']) }}
        @error($name) class="border-red-500" @enderror
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            <option 
                value="{{ is_array($option) ? $option[$valueField] : $option->$valueField }}" 
                {{ old($name) == (is_array($option) ? $option[$valueField] : $option->$valueField) ? 'selected' : '' }}
            >
                {{ is_array($option) ? $option[$textField] : $option->$textField }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>