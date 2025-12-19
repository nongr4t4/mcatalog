@extends('layouts.admin')

@section('title', 'Категорія: ' . $category->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Основна інформація -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Інформація категорії -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h2 class="text-3xl font-bold text-ui-fg mb-2">{{ $category->name }}</h2>
            <p class="text-ui-muted mb-4">{{ $category->description ?? 'Немає опису' }}</p>

            <div class="mt-6 pt-6 border-t border-ui-border/40">
                <p class="text-sm text-ui-muted mb-2">Слаг</p>
                <p class="font-mono text-ui-fg bg-ui-bg/60 border border-ui-border/40 p-2 rounded">{{ $category->slug }}</p>
            </div>
        </div>

        <!-- Товари в категорії -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h3 class="text-xl font-bold text-ui-fg mb-4 flex items-center">
                <i class="fas fa-boxes mr-3 text-ui-accent"></i>
                Товари ({{ $category->products->count() }})
            </h3>

            @if($category->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ui-bg/60 border-b border-ui-border/40">
                        <tr>
                            <th class="px-4 py-2 text-left text-ui-fg font-semibold">Назва</th>
                            <th class="px-4 py-2 text-left text-ui-fg font-semibold">Ціна</th>
                            <th class="px-4 py-2 text-left text-ui-fg font-semibold">Склад</th>
                            <th class="px-4 py-2 text-left text-ui-fg font-semibold">Статус</th>
                            <th class="px-4 py-2 text-right text-ui-fg font-semibold">Дія</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ui-border/30">
                        @foreach($category->products as $product)
                        <tr class="hover:bg-ui-bg/40">
                            <td class="px-4 py-2 font-medium text-ui-fg">{{ $product->name }}</td>
                            <td class="px-4 py-2 text-ui-muted">{{ number_format($product->price, 2, '.', ' ') }} ₴</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-ui-bg/60 border {{ $product->stock > 10 ? 'border-ui-accent text-ui-accent' : ($product->stock > 0 ? 'border-ui-border/40 text-ui-fg' : 'border-ui-accent2 text-ui-accent2') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-ui-bg/60 border {{ !$product->is_archived ? 'border-ui-accent text-ui-accent' : 'border-ui-border/40 text-ui-muted' }}">
                                    {{ !$product->is_archived ? 'Активний' : 'Архівований' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-ui-accent hover:brightness-110">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 bg-ui-bg/40 border border-ui-border/40 rounded-lg shadow-xl shadow-black/50">
                <i class="fas fa-inbox text-ui-border text-3xl mb-2"></i>
                <p class="text-ui-muted font-medium">У цій категорії немає товарів</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Сайдбар -->
    <div class="space-y-6">
        <!-- Інформація -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4">Інформація</h3>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-ui-muted mb-1">ID</p>
                    <p class="font-mono text-ui-fg">{{ $category->id }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Товарів у категорії</p>
                    <p class="text-xl font-bold text-ui-accent">{{ $category->products->count() }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Створено</p>
                    <p class="text-ui-fg">{{ $category->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Оновлено</p>
                    <p class="text-ui-fg">{{ $category->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Дії -->
        <div class="space-y-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="block w-full text-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
                <i class="fas fa-edit mr-2"></i> Редагувати
            </a>
            <a href="{{ route('admin.categories.index') }}" class="block w-full text-center px-4 py-2 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Повернутися
            </a>
            @if($category->products->count() === 0)
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Видалити цю категорію?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-ui-accent2 text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
                    <i class="fas fa-trash mr-2"></i> Видалити
                </button>
            </form>
            @else
            <button disabled class="w-full px-4 py-2 bg-ui-border/40 text-ui-muted rounded-lg cursor-not-allowed font-medium" title="Не можна видалити категорію з товарами">
                <i class="fas fa-trash mr-2"></i> Видалити
            </button>
            @endif
        </div>
    </div>
</div>
@endsection
