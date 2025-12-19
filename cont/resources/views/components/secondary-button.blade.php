<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-ui-panel border border-ui-border/40 rounded-md font-semibold text-xs text-ui-fg uppercase tracking-widest shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-ui-accent focus:ring-offset-2 focus:ring-offset-ui-bg disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
