@extends('layouts.admin')

@section('title', 'Редагувати категорію')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Назва категорії -->
            <div>
                  <label for="name" class="block text-sm font-semibold text-ui-fg mb-2">Назва категорії *</label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $category->name) }}"
                      class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg placeholder-ui-muted border @error('name') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition"
                       placeholder="Назва категорії">
                @error('name')
                  <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Опис -->
            <div>
                <label for="description" class="block text-sm font-semibold text-ui-fg mb-2">Опис</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg placeholder-ui-muted border @error('description') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition"
                          placeholder="Опис категорії">{{ old('description', $category->description) }}</textarea>
                @error('description')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Кнопки дії -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-ui-border/40">
                <a href="{{ route('admin.categories.index') }}"
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
