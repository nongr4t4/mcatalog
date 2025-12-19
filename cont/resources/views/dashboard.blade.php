<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ui-fg leading-tight">
            {{ __('Головна') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-ui-bg border border-ui-border/40 overflow-hidden sm:rounded-lg shadow-xl shadow-black/50">
                <div class="p-6 text-ui-fg">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-ui-bg/60 border border-ui-border/40 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-ui-accent text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-ui-fg mb-2">{{ __('Вітаємо!') }}</h3>
                        <p class="text-ui-muted mb-6">{{ __('Ви успішно увійшли в систему.') }}</p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition">
                            <i class="fas fa-store mr-2"></i>{{ __('Перейти до каталогу') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
