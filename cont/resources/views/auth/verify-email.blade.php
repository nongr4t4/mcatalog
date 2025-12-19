<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-16 h-16 bg-ui-bg/60 border border-ui-border/40 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope text-ui-accent text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-ui-fg">Підтвердження email вимкнено</h2>
        <p class="text-ui-muted text-sm mt-2">
            {{ __('У цій версії проєкту підтвердження електронної пошти не використовується.') }}
        </p>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-ui-accent text-ui-bg rounded-md hover:brightness-110 transition w-full sm:w-auto">
            <i class="fas fa-store mr-2"></i>{{ __('Перейти в каталог') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm text-ui-muted hover:text-ui-fg transition">
                <i class="fas fa-sign-out-alt mr-1"></i>{{ __('Вийти') }}
            </button>
        </form>
    </div>
</x-guest-layout>
