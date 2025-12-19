@extends('layouts.admin')

@section('title', 'Категорії')

@section('header-actions')
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
        <i class="fas fa-plus mr-2"></i> Додати категорію
    </a>
@endsection

@section('content')
<div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 overflow-hidden">
    @if($categories->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-ui-bg/60 border-b border-ui-border/40">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Назва</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Товарів</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Опис</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-ui-fg">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ui-border/30">
                @foreach($categories as $category)
                    <tr class="hover:bg-ui-bg/40 transition">
                        <td class="px-6 py-4 text-sm font-medium text-ui-fg">
                            <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="text-ui-accent hover:brightness-110">
                                {{ $category->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-ui-bg/60 border border-ui-border/40 text-ui-accent">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">
                            {{ Str::limit($category->description, 50) ?? 'Немає опису' }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center text-ui-accent hover:brightness-110 transition" title="Редагувати">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Видалити цю категорію? Товари залишатимуться.');">
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

    @if($categories->hasPages())
    <div class="bg-ui-bg/60 px-6 py-4 border-t border-ui-border/40">
        {{ $categories->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-tags text-ui-border text-4xl mb-4"></i>
        <p class="text-ui-muted font-medium">Категорії не знайдені</p>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition">
            <i class="fas fa-plus mr-2"></i> Додати першу категорію
        </a>
    </div>
    @endif
</div>
@endsection
