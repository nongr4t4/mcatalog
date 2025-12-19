<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-ui-accent2 border border-transparent rounded-md font-semibold text-xs text-ui-bg uppercase tracking-widest hover:brightness-110 active:brightness-95 focus:outline-none focus:ring-2 focus:ring-ui-accent2/40 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
