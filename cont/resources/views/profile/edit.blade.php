<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-ui-bg/60 border border-ui-border/40 overflow-hidden">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Аватар" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="text-sm text-ui-muted">Особистий кабінет</p>
                    <h2 class="text-3xl font-bold text-ui-fg leading-tight">{{ auth()->user()->name }}</h2>
                    <p class="text-ui-muted">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-ui-bg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div class="p-6 bg-ui-bg border border-ui-border/40 rounded-2xl shadow-xl shadow-black/50">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="p-6 bg-ui-bg border border-ui-border/40 rounded-2xl shadow-xl shadow-black/50">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="p-6 bg-ui-bg border border-ui-border/40 rounded-2xl shadow-xl shadow-black/50">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
