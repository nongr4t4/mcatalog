<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-ui-accent border border-transparent rounded-md font-semibold text-xs text-ui-bg uppercase tracking-widest hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-ui-accent focus:ring-offset-2 focus:ring-offset-ui-bg transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
