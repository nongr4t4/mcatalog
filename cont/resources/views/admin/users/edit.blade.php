@extends('layouts.admin')

@section('title', 'Редагувати користувача')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Аватар -->
            <div>
                <label class="block text-sm font-semibold text-ui-fg mb-3">Аватар користувача</label>
                <div class="flex items-center gap-4">
                    <div class="h-20 w-20 rounded-full overflow-hidden bg-ui-bg/60 border border-ui-border/40 flex-shrink-0">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="avatar" accept="image/*"
                               class="block w-full text-sm text-ui-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-ui-border/40 file:text-sm file:font-semibold file:bg-ui-bg/60 file:text-ui-fg hover:file:bg-ui-bg/80">
                        <p class="text-xs text-ui-muted mt-1">JPG, PNG до 2MB</p>
                        @error('avatar')
                        <span class="text-ui-accent2 text-sm block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Основна інформація -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                      <label for="name" class="block text-sm font-semibold text-ui-fg mb-2">Ім'я користувача *</label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $user->name) }}"
                          class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg border @error('name') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition">
                    @error('name')
                      <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                      <label for="email" class="block text-sm font-semibold text-ui-fg mb-2">Email *</label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email', $user->email) }}"
                          class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg border @error('email') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition">
                    @error('email')
                      <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Роль -->
            <div>
                <label for="role" class="block text-sm font-semibold text-ui-fg mb-2">Роль *</label>
                <select name="role" id="role" required
                        class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg border @error('role') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition">
                    <option value="">-- Виберіть роль --</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Зміна пароля -->
            <div class="border-t border-ui-border/40 pt-6">
                <h3 class="text-lg font-semibold text-ui-fg mb-4">Зміна пароля</h3>
            
                <div>
                    <label for="password" class="block text-sm font-semibold text-ui-fg mb-2">Новий пароль</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg placeholder-ui-muted border @error('password') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition"
                           placeholder="Залиште пустим для збереження поточного пароля">
                    @error('password')
                    <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Кнопки дії -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-ui-border/40">
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 border border-ui-border/40 rounded-lg text-ui-fg font-medium hover:bg-ui-bg/40 transition">
                    <i class="fas fa-times mr-2"></i> Скасувати
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-ui-accent text-ui-bg rounded-lg font-medium hover:brightness-110 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Зберегти зміни
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
