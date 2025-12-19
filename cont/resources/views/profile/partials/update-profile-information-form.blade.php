<section>
    <header>
       <h2 class="text-lg font-medium text-ui-fg">
             {{ __('Профіль') }}
        </h2>

       <p class="mt-1 text-sm text-ui-muted">
             {{ __('Оновіть дані профілю та електронну пошту.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Імʼя')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="avatar" :value="__('Аватар')" />
            <div class="flex items-center gap-4 mt-2">
                <img src="{{ $user->avatar_url }}" alt="Аватар" class="w-14 h-14 rounded-full object-cover border border-ui-border/40">
                <div class="space-y-2">
                    <x-text-input id="avatar" name="avatar" type="file" accept="image/*" class="block w-full text-sm" />
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1" class="rounded border-ui-border/40 text-ui-accent bg-ui-bg/60 shadow-sm focus:ring-ui-accent/40">
                        <label for="remove_avatar" class="text-sm text-ui-fg">{{ __('Видалити поточний аватар') }}</label>
                    </div>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Ел. пошта')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Зберегти') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-ui-muted"
                    >{{ __('Збережено.') }}</p>
            @endif
        </div>
    </form>
</section>
