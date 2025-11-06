@props([
    'name',
    'label',
    'required' => false,
    'placeholder' => '',
    'value' => '',
    'rows' => 3,
])

<div class="mb-6">
    <label class="block text-sm font-medium mb-2 text-black">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <textarea 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent']) }}
        @error($name) class="border-red-500" @enderror
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>