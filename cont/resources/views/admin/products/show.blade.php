@extends('layouts.admin')

@section('title', 'Товар: ' . $product->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Основна інформація -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Загальна інформація -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-6">
            <h2 class="text-2xl font-bold text-ui-fg mb-4">{{ $product->name }}</h2>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-ui-muted mb-1">Опис</p>
                    <p class="text-ui-fg">{{ $product->description ?? 'Немає опису' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-ui-muted mb-1">Ціна</p>
                        <p class="text-2xl font-bold text-ui-accent">{{ number_format($product->price, 2, '.', ' ') }} ₴</p>
                    </div>
                    <div>
                        <p class="text-sm text-ui-muted mb-1">На складі</p>
                        <p class="text-2xl font-bold {{ $product->stock > 10 ? 'text-ui-accent' : ($product->stock > 0 ? 'text-ui-fg' : 'text-ui-accent2') }}">
                            {{ $product->stock }}
                        </p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-ui-muted mb-1">Статус</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border border-ui-border/40 {{ !$product->is_archived ? 'text-ui-accent' : 'text-ui-muted' }}">
                        {{ !$product->is_archived ? 'Активний' : 'Архів' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Категорії -->
        @if($product->categories->count() > 0)
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4">Категорії</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($product->categories as $category)
                    <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-3 py-1 bg-ui-bg text-ui-fg border border-ui-border/40 rounded-full text-sm font-medium hover:border-ui-border/70 transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Галерея фотографій -->
        @if($product->photos->count() > 0)
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4">Фотографії товару</h3>
            <div class="space-y-4">
                <!-- Основна фотографія -->
                @php
                    $mainPhoto = $product->photos->where('is_main', true)->first();
                @endphp
                @if($mainPhoto)
                <div class="mb-4">
                    <p class="text-sm text-ui-muted mb-2">Основне зображення</p>
                    <img src="{{ Storage::url($mainPhoto->path) }}" 
                        class="w-full rounded-lg border-2 border-ui-accent" 
                         alt="Основне фото">
                </div>
                @endif
                
                <!-- Галерея інших фотографій -->
                @if($product->photos->count() > 1)
                <div>
                    <p class="text-sm text-ui-muted mb-2">Інші фотографії</p>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($product->photos->where('is_main', false)->sortBy('order') as $photo)
                        <div class="relative group">
                            <img src="{{ Storage::url($photo->path) }}" 
                                 class="w-full h-24 object-cover rounded-lg border border-ui-border/40 cursor-pointer hover:border-ui-accent transition" 
                                 alt="Фото товару"
                                 onclick="openLightbox('{{ Storage::url($photo->path) }}')">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Сайдбар -->
    <div class="space-y-6">
        <!-- Дати -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4">Інформація</h3>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-ui-muted mb-1">ID</p>
                    <p class="font-mono text-ui-fg">{{ $product->id }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Створено</p>
                    <p class="text-ui-fg">{{ $product->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Оновлено</p>
                    <p class="text-ui-fg">{{ $product->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Дії -->
        <div class="space-y-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="block w-full text-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition font-medium">
                <i class="fas fa-edit mr-2"></i> Редагувати
            </a>
            <a href="{{ route('catalog.show', ['product' => $product->id, 'preview' => 1]) }}" target="_blank" class="block w-full text-center px-4 py-2 bg-ui-panel text-ui-fg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 hover:bg-ui-bg/40 transition font-medium">
                <i class="fas fa-eye mr-2"></i> Переглянути як у каталозі
            </a>
            <a href="{{ route('catalog.show', $product) }}#reviews" target="_blank" class="block w-full text-center px-4 py-2 border border-ui-border/40 text-ui-accent rounded-lg hover:bg-ui-bg/40 transition font-medium">
                <i class="fas fa-comments mr-2"></i> Відгуки в каталозі
            </a>
            <a href="{{ route('admin.products.index') }}" class="block w-full text-center px-4 py-2 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Повернутися
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Видалити цей товар? Це незворотна дія.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-ui-accent2 text-ui-bg rounded-lg hover:brightness-95 transition font-medium">
                    <i class="fas fa-trash mr-2"></i> Видалити
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Відгуки -->
<div class="mt-8 bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-bold text-ui-fg">Відгуки покупців</h3>
            <p class="text-sm text-ui-muted">Один відгук від користувача на товар.</p>
        </div>
        <span class="px-3 py-1 text-sm rounded-full bg-ui-bg text-ui-fg border border-ui-border/40">{{ $product->reviews->count() }} відгуків</span>
    </div>

    <div class="divide-y divide-ui-border/40">
        @forelse($product->reviews as $review)
            <div class="py-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="font-semibold text-ui-fg">{{ $review->user->name ?? 'Користувач' }}</div>
                        <div class="flex items-center text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->stars ? 'fas fa-star' : 'far fa-star text-ui-border' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-ui-muted">{{ $review->stars }}/5</span>
                    </div>
                    <span class="text-xs text-ui-border">Оновлено: {{ $review->updated_at->format('d.m.Y H:i') }}</span>
                </div>
                @if($review->comment)
                    <p class="text-ui-fg">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="py-6 text-ui-muted">Відгуків ще немає.</p>
        @endforelse
    </div>
</div>

<script>
// Глобальні функції ініціалізуються в app.js через initGlobalHelpers()
</script>
@endsection
