@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'icon' => null,
    'placeholder' => '',
    'required' => false,
    'wire' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            <span class="flex items-center">
                @if($icon)
                    {{ $icon }}
                @endif
                {{ $label }}
                @if($required)
                    <span class="text-red-500 ml-1">*</span>
                @endif
            </span>
        </label>
    @endif
    
    <div class="relative">
        <input 
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            @if($wire)
                wire:model{{ $wire === 'live' ? '.live' : '' }}="{{ $name }}"
            @endif
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm']) }}
        />
    </div>
    
    @error($name)
        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
    @enderror
</div>