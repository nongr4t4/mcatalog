@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-ui-bg text-ui-fg border-ui-border/40 placeholder-ui-muted/70 focus:border-ui-accent focus:ring-ui-accent rounded-md shadow-sm']) }}>
