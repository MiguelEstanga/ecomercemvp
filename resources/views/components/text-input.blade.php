@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'value' => '',
])

<div class="mb-6">
    <label class="block text-sm font-medium mb-2 text-black">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <input 
        type="{{ $type }}"
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent']) }}
        @error($name) class="border-red-500" @enderror
    />
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>