@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 bg-ui-bg/60 text-ui-fg border-l-4 border-ui-accent transition duration-150 ease-in-out'
            : 'flex items-center px-4 py-3 text-ui-muted hover:bg-ui-bg/60 hover:text-ui-fg transition duration-150 ease-in-out border-l-4 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="fas {{ $icon }} w-5 text-center mr-3"></i>
    @endif
    <span class="font-medium text-sm">{{ $slot }}</span>
</a>
