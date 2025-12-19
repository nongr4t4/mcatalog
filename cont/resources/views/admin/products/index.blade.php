@extends('layouts.admin')

@section('title', 'Товари')

@section('header-actions')
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition font-medium">
        <i class="fas fa-plus mr-2"></i> Додати товар
    </a>
@endsection

@section('content')
<div class="bg-ui-bg rounded-lg shadow-xl shadow-black/50 overflow-hidden border border-ui-border/40">
    <div class="px-6 pt-6 pb-4 border-b border-ui-border/40 bg-ui-bg">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-ui-muted mb-2">Пошук (назва або опис)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Пошук..."
                       class="w-full px-4 py-2 border border-ui-border/40 bg-ui-bg text-ui-fg rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-ui-muted mb-2">Ціна, ₴ (від / до)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ui-muted">₴</span>
                        <input type="number" name="price_from" step="0.01" min="0" value="{{ request('price_from') }}" placeholder="Від"
                               class="w-full pl-8 pr-4 py-2 border border-ui-border/40 bg-ui-bg text-ui-fg rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ui-muted">₴</span>
                        <input type="number" name="price_to" step="0.01" min="0" value="{{ request('price_to') }}" placeholder="До"
                               class="w-full pl-8 pr-4 py-2 border border-ui-border/40 bg-ui-bg text-ui-fg rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ui-muted mb-2">Категорія</label>
                <select name="category" class="w-full px-4 py-2 border border-ui-border/40 bg-ui-bg text-ui-fg rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
                    <option value="">Усі</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ui-muted mb-2">Рейтинг від</label>
                <select name="stars" class="w-full px-4 py-2 border border-ui-border/40 bg-ui-bg text-ui-fg rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
                    @foreach([0=>'Будь-який',5=>'5 зірок',4=>'4+ зірки',3=>'3+ зірки',2=>'2+ зірки',1=>'1+ зірка'] as $val=>$label)
                        <option value="{{ $val }}" {{ (string)request('stars','0') === (string)$val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ui-muted mb-2">Сортування</label>
                <select name="sort" class="w-full px-4 py-2 border border-ui-border/40 bg-ui-bg text-ui-fg rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Найновіші</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Ціна ↑</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Ціна ↓</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Назва A-Z</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Назва Z-A</option>
                </select>
            </div>
            <div class="lg:col-span-6 flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition"><i class="fas fa-filter mr-2"></i>Фільтрувати</button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition">Скинути</a>
            </div>
        </form>
    </div>
    @if($products->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-ui-bg border-b border-ui-border/40">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-muted">Назва</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-muted">Ціна</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-muted">Склад</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-muted">Статус</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-muted">Категорії</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-ui-muted">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ui-border/40">
                @foreach($products as $product)
                    <tr class="hover:bg-ui-bg/30 transition">
                        <td class="px-6 py-4 text-sm font-medium text-ui-fg">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-ui-muted">{{ number_format($product->price, 2, '.', ' ') }} ₴</td>
                        <td class="px-6 py-4 text-sm text-ui-muted">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-ui-border/40 {{ $product->stock > 10 ? 'bg-ui-bg text-ui-accent' : ($product->stock > 0 ? 'bg-ui-bg text-ui-fg' : 'bg-ui-bg text-ui-accent2') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-ui-border/40 {{ !$product->is_archived ? 'text-ui-accent' : 'text-ui-muted' }}">
                                {{ !$product->is_archived ? 'Активний' : 'Архів' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">
                            @if($product->categories->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->categories->take(2) as $category)
                                        <span class="inline-block bg-ui-bg text-ui-fg border border-ui-border/40 px-2 py-1 rounded text-xs">{{ $category->name }}</span>
                                    @endforeach
                                    @if($product->categories->count() > 2)
                                        <span class="inline-block text-ui-border text-xs">+{{ $product->categories->count() - 2 }} ще</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-ui-border text-xs">Немає</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center text-ui-accent hover:brightness-95 transition" title="Редагувати">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Видалити цей товар?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-ui-accent2 hover:brightness-110 transition" title="Видалити">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="bg-ui-bg px-6 py-4 border-t border-ui-border/40">
        {{ $products->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-inbox text-ui-border text-4xl mb-4"></i>
        <p class="text-ui-muted font-medium">Товари не знайдені</p>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition">
            <i class="fas fa-plus mr-2"></i> Додати перший товар
        </a>
    </div>
    @endif
</div>
@endsection