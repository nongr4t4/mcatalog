<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-ui-fg">Відновлення пароля</h2>
        <p class="text-ui-muted text-sm mt-2">
            {{ __('Забули пароль? Введіть вашу електронну пошту, і ми надішлемо посилання для відновлення.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Електронна пошта')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="example@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                <i class="fas fa-envelope mr-2"></i>{{ __('Надіслати посилання') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-ui-accent hover:brightness-110 transition">
            <i class="fas fa-arrow-left mr-1"></i>{{ __('Повернутися до входу') }}
        </a>
    </div>
</x-guest-layout>
