<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-16 h-16 bg-ui-bg/60 border border-ui-border/40 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-lock text-ui-accent2 text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-ui-fg">Підтвердження</h2>
        <p class="text-ui-muted text-sm mt-2">
            {{ __('Це захищена область. Будь ласка, підтвердіть пароль перед продовженням.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Пароль -->
        <div>
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Введіть ваш пароль" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                <i class="fas fa-check mr-2"></i>{{ __('Підтвердити') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
