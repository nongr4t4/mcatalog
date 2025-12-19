<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-ui-fg">Вхід</h2>
        <p class="text-ui-muted text-sm mt-1">Увійдіть до вашого акаунту</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Електронна пошта')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="example@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Пароль -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Введіть пароль" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Запам'ятати мене -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-ui-border/40 text-ui-accent bg-ui-bg/60 shadow-sm focus:ring-ui-accent/40" name="remember">
                <span class="ms-2 text-sm text-ui-muted">{{ __('Запам\'ятати мене') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-ui-accent hover:brightness-110 transition" href="{{ route('password.request') }}">
                    {{ __('Забули пароль?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Увійти') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center border-t border-ui-border/40 pt-6">
        <p class="text-sm text-ui-muted mb-3">{{ __('Ще не маєте акаунту?') }}</p>
        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-ui-accent border border-transparent rounded-md font-semibold text-xs text-ui-bg uppercase tracking-widest hover:brightness-110 transition">
            <i class="fas fa-user-plus mr-2"></i>{{ __('Зареєструватися') }}
        </a>
    </div>
</x-guest-layout>
