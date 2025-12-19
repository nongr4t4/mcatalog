@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-ui-accent text-sm font-medium leading-5 text-ui-fg focus:outline-none focus:border-ui-accent transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-ui-muted hover:text-ui-fg hover:border-ui-border/60 focus:outline-none focus:text-ui-fg focus:border-ui-border/60 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
