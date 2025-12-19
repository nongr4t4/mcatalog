<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-ui-fg">Реєстрація</h2>
        <p class="text-ui-muted text-sm mt-1">Створіть акаунт для покупок</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Ім'я -->
        <div>
            <x-input-label for="name" :value="__('Ім\'я')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Введіть ваше ім'я" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Електронна пошта')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="example@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Пароль -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Мінімум 8 символів" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Підтвердження пароля -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Підтвердіть пароль')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Повторіть пароль" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                <i class="fas fa-user-plus mr-2"></i>{{ __('Зареєструватися') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center border-t border-ui-border/40 pt-6">
        <p class="text-sm text-ui-muted mb-3">{{ __('Вже маєте акаунт?') }}</p>
        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-ui-border/40 rounded-md font-semibold text-xs text-ui-fg uppercase tracking-widest hover:bg-ui-bg/40 transition">
            <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Увійти') }}
        </a>
    </div>
</x-guest-layout>
