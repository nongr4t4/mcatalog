<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-ui-fg">Новий пароль</h2>
        <p class="text-ui-muted text-sm mt-1">Введіть новий пароль для вашого акаунту</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Електронна пошта')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Пароль -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Новий пароль')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Мінімум 8 символів" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Підтвердження пароля -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Підтвердіть пароль')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Повторіть новий пароль" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                <i class="fas fa-key mr-2"></i>{{ __('Зберегти пароль') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
