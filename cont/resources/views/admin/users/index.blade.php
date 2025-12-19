@extends('layouts.admin')

@section('title', 'Користувачі')

@section('header-actions')
    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
        <i class="fas fa-store mr-2"></i> На сайт
    </a>
@endsection

@section('content')
<div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 overflow-hidden">
    <div class="px-6 pt-6 pb-4 border-b border-ui-border/40 bg-ui-bg/60">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 md:flex-row md:items-end md:gap-4">
            <div class="flex-1">
                <label for="user-search" class="block text-sm font-semibold text-ui-fg mb-2">Пошук користувача</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-ui-border"></i>
                    </div>
                    <input id="user-search" type="text" name="search" value="{{ request('search') }}" placeholder="Ім'я або email"
                           class="w-full pl-10 pr-4 py-2 bg-ui-bg/60 text-ui-fg placeholder-ui-muted border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
                    <i class="fas fa-search mr-2"></i> Знайти
                </button>
                @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition font-medium">
                    <i class="fas fa-times mr-2"></i> Скинути
                </a>
                @endif
            </div>
        </form>
    </div>
    @if($users->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-ui-bg/60 border-b border-ui-border/40">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Користувач</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Роль</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Замовлень</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Реєстрація</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-ui-fg">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ui-border/30">
                @foreach($users as $user)
                    <tr class="hover:bg-ui-bg/40 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full overflow-hidden bg-ui-bg/60 border border-ui-border/40 flex-shrink-0">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold text-ui-fg">
                                        <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="text-ui-accent hover:brightness-110" title="Переглянути замовлення користувача">
                                            {{ $user->name }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-ui-muted">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-ui-bg/60 border {{ $user->role === 'admin' ? 'border-ui-accent2 text-ui-accent2' : 'border-ui-accent text-ui-accent' }}">
                                {{ $user->role === 'admin' ? 'Адміністратор' : 'Користувач' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-ui-bg/60 border border-ui-accent text-ui-accent">
                                {{ $user->orders_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center text-ui-accent hover:brightness-110 transition" title="Редагувати">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Видалити цього користувача? Це незворотна дія.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-ui-accent2 hover:brightness-110 transition" title="Видалити">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-ui-border cursor-not-allowed" title="Ви не можете видалити себе">
                                    <i class="fas fa-trash"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="bg-ui-bg/60 px-6 py-4 border-t border-ui-border/40">
        {{ $users->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-users text-ui-border text-4xl mb-4"></i>
        <p class="text-ui-muted font-medium">Користувачів не знайдено</p>
    </div>
    @endif
</div>
@endsection
