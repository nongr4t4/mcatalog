@extends('layouts.admin')

@section('title', 'Редагувати товар')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Назва -->
            <div>
                  <label for="name" class="block text-sm font-semibold text-ui-muted mb-2">Назва товару *</label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $product->name) }}"
                      class="w-full px-4 py-2 bg-ui-bg text-ui-fg border @error('name') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition"
                       placeholder="Назва товару">
                @error('name')
                  <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Опис -->
            <div>
                <label for="description" class="block text-sm font-semibold text-ui-muted mb-2">Опис</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 bg-ui-bg text-ui-fg border @error('description') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition"
                          placeholder="Детальний опис товару">{{ old('description', $product->description) }}</textarea>
                @error('description')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ціна та Склад -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                      <label for="price" class="block text-sm font-semibold text-ui-muted mb-2">Ціна (₴) *</label>
                    <input type="number" step="0.01" name="price" id="price" required
                           value="{{ old('price', $product->price) }}"
                          class="w-full px-4 py-2 bg-ui-bg text-ui-fg border @error('price') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition"
                           placeholder="0.00">
                    @error('price')
                      <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-semibold text-ui-muted mb-2">Кількість на складі *</label>
                    <input type="number" name="stock" id="stock" required
                           value="{{ old('stock', $product->stock) }}"
                           class="w-full px-4 py-2 bg-ui-bg text-ui-fg border @error('stock') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition"
                           placeholder="0">
                    @error('stock')
                    <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Категорії -->
            <div>
                <label for="categories" class="block text-sm font-semibold text-ui-muted mb-2">Категорії</label>
                <select name="categories[]" id="categories" multiple
                        class="w-full px-4 py-2 bg-ui-bg text-ui-fg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ $product->categories->contains($category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-ui-muted mt-1">Утримуйте Ctrl для вибору кількох категорій</p>
            </div>

            <!-- Активність -->
            <div class="flex items-center">
                  <input type="checkbox" name="is_archived" id="is_archived" value="1"
                      {{ old('is_archived', $product->is_archived) ? 'checked' : '' }}
                     class="w-4 h-4 text-ui-accent border-ui-border/40 rounded focus:ring-ui-accent">
                <label for="is_archived" class="ml-2 text-sm text-ui-fg font-medium">Архівований товар</label>
            </div>

            <!-- Поточні фотографії -->
            @if($product->photos->count() > 0)
            <div>
                <label class="block text-sm font-semibold text-ui-muted mb-2">Поточні фотографії</label>
                <p class="text-xs text-ui-muted mb-3">Порядок фото змінюється кнопками ↑ ↓. Видалення застосовується тільки після збереження (можна відновити до цього).</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($product->photos->sortBy('order') as $photo)
                    <div class="relative group" data-photo-item data-photo-id="{{ $photo->id }}">
                        <img src="{{ Storage::url($photo->path) }}" 
                             class="w-full h-24 object-cover rounded-lg border border-ui-border/40" 
                             alt="Фото товару">
                        @if($photo->is_main)
                        <span class="absolute top-1 right-1 bg-ui-accent text-ui-bg text-xs px-2 py-1 rounded">Основне</span>
                        @endif
                        <div class="mt-2 space-y-1 bg-ui-bg/90 border border-ui-border/40 rounded p-2 shadow-sm">
                            <label class="flex items-center text-sm text-ui-fg gap-2">
                                <input type="radio" name="main_photo" value="{{ $photo->id }}" {{ $photo->is_main ? 'checked' : '' }} class="text-ui-accent border-ui-border/40 focus:ring-ui-accent">
                                Основне фото
                            </label>

                            {{-- Порядок (записується приховано; UI керування — кнопки нижче) --}}
                            <input type="hidden" name="photo_order[{{ $photo->id }}]" value="{{ $photo->order ?? 0 }}" data-photo-order-input>

                            {{-- Стан "буде видалено" (відправляється лише якщо не disabled) --}}
                            <input type="hidden" name="photos_delete[]" value="{{ $photo->id }}" data-photo-delete-input disabled>

                            <div class="flex items-center justify-between gap-2 pt-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-ui-muted">Порядок:</span>
                                    <span class="text-[11px] font-semibold text-ui-fg" data-photo-order-badge></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="px-2 py-1 text-xs text-ui-accent hover:brightness-95" data-photo-move="up" aria-label="Підняти">↑</button>
                                    <button type="button" class="px-2 py-1 text-xs text-ui-accent hover:brightness-95" data-photo-move="down" aria-label="Опустити">↓</button>
                                </div>
                            </div>

                            <button type="button" class="text-ui-accent2 hover:brightness-110 text-xs font-semibold" data-photo-delete-btn>
                                Видалити
                            </button>
                            <p class="hidden text-[11px] text-ui-accent2" data-photo-delete-note>Буде видалено після збереження</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Додати нові фотографії -->
            <div>
                <label for="photos" class="block text-sm font-semibold text-ui-muted mb-2">Додати нові фотографії</label>
                <div class="border-2 border-dashed border-ui-border/40 rounded-lg p-6 text-center cursor-pointer hover:border-ui-accent transition"
                     onclick="document.getElementById('photos').click()">
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden"
                           onchange="handlePhotosChange(event)">
                    <i class="fas fa-cloud-upload-alt text-3xl text-ui-border mb-2 block"></i>
                    <p class="text-ui-fg font-medium">Натисніть для завантаження фото</p>
                    <p class="text-xs text-ui-muted mt-1">Підтримуються: JPG, PNG, GIF (макс 2 МБ)</p>
                </div>
                <input type="hidden" name="main_new_index" id="main_new_index" value="">
                <div id="photoPreview" class="mt-4 space-y-2"></div>
                @error('photos')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
                @error('photos.*')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Кнопки дії -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-ui-border/40">
                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-2 border border-ui-border/40 rounded-lg text-ui-fg font-medium hover:bg-ui-bg/40 transition">
                    <i class="fas fa-times mr-2"></i> Скасувати
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-ui-accent text-ui-bg rounded-lg font-medium hover:brightness-95 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Зберегти зміни
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Ініціалізація менеджерів фото
document.addEventListener('DOMContentLoaded', () => {
    // Нові фото
    const photosManager = new window.ProductPhotosManager();
    photosManager.init();

    // Наявні фото
    const existingPhotos = new window.ExistingPhotosManager();
    existingPhotos.init();
});
</script>
@endsection
