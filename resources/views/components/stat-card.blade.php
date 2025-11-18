@props([
    'title' => 'Título',
    'value' => '0',
    'icon' => 'chart',
    'color' => 'blue',
    'trend' => null,
    'trendUp' => true,
])

@php
    $colors = [
        'blue' => 'from-blue-500 to-blue-600',
        'green' => 'from-green-500 to-green-600',
        'yellow' => 'from-yellow-500 to-yellow-600',
        'red' => 'from-red-500 to-red-600',
        'indigo' => 'from-indigo-500 to-indigo-600',
        'purple' => 'from-purple-500 to-purple-600',
        'pink' => 'from-pink-500 to-pink-600',
    ];
    
    $iconColors = [
        'blue' => 'bg-blue-400',
        'green' => 'bg-green-400',
        'yellow' => 'bg-yellow-400',
        'red' => 'bg-red-400',
        'indigo' => 'bg-indigo-400',
        'purple' => 'bg-purple-400',
        'pink' => 'bg-pink-400',
    ];
    
    $gradientClass = $colors[$color] ?? $colors['blue'];
    $iconBgClass = $iconColors[$color] ?? $iconColors['blue'];
@endphp

<div class="bg-gradient-to-br {{ $gradientClass }} rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-white text-opacity-90 text-sm font-medium uppercase tracking-wide">
                {{ $title }}
            </p>
            <p class="text-3xl font-bold mt-2">{{ $value }}</p>
            
            @if($trend)
                <div class="mt-2 flex items-center text-sm">
                    @if($trendUp)
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    <span>{{ $trend }}</span>
                </div>
            @endif
        </div>
        
        <div class="{{ $iconBgClass }} bg-opacity-30 rounded-full p-3">
            @if(isset($customIcon))
                {{ $customIcon }}
            @else
                @switch($icon)
                    @case('cart')
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @break
                    @case('clock')
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @break
                    @case('check')
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        @break
                    @case('dollar')
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @break
                    @case('users')
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        @break
                    @case('chart')
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        @break
                    @default
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                @endswitch
            @endif
        </div>
    </div>
</div>