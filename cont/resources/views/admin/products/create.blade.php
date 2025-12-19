@extends('layouts.admin')

@section('title', 'Додати новий товар')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Назва -->
            <div>
                  <label for="name" class="block text-sm font-semibold text-ui-muted mb-2">Назва товару *</label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name') }}"
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
                          placeholder="Детальний опис товару">{{ old('description') }}</textarea>
                @error('description')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ціна та Склад -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                      <label for="price" class="block text-sm font-semibold text-ui-muted mb-2">Ціна (₴) *</label>
                    <input type="number" step="0.01" name="price" id="price" required
                           value="{{ old('price') }}"
                          class="w-full px-4 py-2 bg-ui-bg text-ui-fg border @error('price') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition"
                           placeholder="0.00">
                    @error('price')
                      <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-semibold text-ui-muted mb-2">Кількість на складі *</label>
                    <input type="number" name="stock" id="stock" required
                           value="{{ old('stock') }}"
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
                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-ui-muted mt-1">Утримуйте Ctrl для вибору кількох категорій</p>
            </div>

            <!-- Активність -->
            <div class="flex items-center">
                  <input type="checkbox" name="is_archived" id="is_archived" value="1"
                      {{ old('is_archived') ? 'checked' : '' }}
                     class="w-4 h-4 text-ui-accent border-ui-border/40 rounded focus:ring-ui-accent">
                <label for="is_archived" class="ml-2 text-sm text-ui-fg font-medium">Архівований товар</label>
            </div>

            <!-- Фотографії -->
            <div>
                <label for="photos" class="block text-sm font-semibold text-ui-muted mb-2">Фотографії товару</label>
                <div class="border-2 border-dashed border-ui-border/40 rounded-lg p-6 text-center cursor-pointer hover:border-ui-accent transition"
                     onclick="document.getElementById('photos').click()">
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden"
                           onchange="handlePhotosChange(event)">
                    <i class="fas fa-cloud-upload-alt text-3xl text-ui-border mb-2 block"></i>
                    <p class="text-ui-fg font-medium">Натисніть для завантаження фото</p>
                    <p class="text-xs text-ui-muted mt-1">Підтримуються: JPG, PNG, GIF (макс 2 МБ)</p>
                </div>
                <input type="hidden" name="main_new_index" id="main_new_index" value="0">
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
                    <i class="fas fa-save mr-2"></i> Створити товар
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Ініціалізація менеджера фото
document.addEventListener('DOMContentLoaded', () => {
    const photosManager = new window.ProductPhotosManager();
    photosManager.init();
});
</script>
@endsection
