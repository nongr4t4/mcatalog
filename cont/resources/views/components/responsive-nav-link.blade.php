@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-ui-accent text-start text-base font-medium text-ui-fg bg-ui-panel/40 focus:outline-none focus:text-ui-fg focus:bg-ui-panel/40 focus:border-ui-accent transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-ui-muted hover:text-ui-fg hover:bg-ui-panel/40 hover:border-ui-border/60 focus:outline-none focus:text-ui-fg focus:bg-ui-panel/40 focus:border-ui-border/60 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
