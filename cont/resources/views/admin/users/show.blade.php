@extends('layouts.admin')

@section('title', 'Користувач: ' . $user->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Основна інформація -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Профіль користувача -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <div class="flex items-start gap-4 mb-6">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover border border-ui-border/40">
                <div>
                    <h2 class="text-3xl font-bold text-ui-fg">{{ $user->name }}</h2>
                    <p class="text-ui-muted break-all">{{ $user->email }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-ui-bg/60 border {{ $user->role === 'admin' ? 'border-ui-accent2 text-ui-accent2' : 'border-ui-accent text-ui-accent' }}">
                            {{ $user->role === 'admin' ? 'Адміністратор' : 'Користувач' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-ui-border/40">
                <h3 class="text-lg font-bold text-ui-fg mb-4">Статистика</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-ui-accent">{{ $user->orders->count() ?? 0 }}</p>
                        <p class="text-sm text-ui-muted">Замовлень</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-ui-accent">{{ $user->created_at->diffInDays() }}</p>
                        <p class="text-sm text-ui-muted">Днів користувача</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-ui-accent2">{{ number_format($user->orders->sum('total_amount') ?? 0, 0) }} ₴</p>
                        <p class="text-sm text-ui-muted">Витрачено</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Останні замовлення -->
        @if($user->orders->count() > 0)
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h3 class="text-xl font-bold text-ui-fg mb-4 flex items-center">
                <i class="fas fa-shopping-cart mr-3 text-ui-accent"></i>
                Останні замовлення
            </h3>

            <div class="space-y-3">
                @foreach($user->orders->take(5) as $order)
                <div class="flex justify-between items-center p-3 bg-ui-bg/40 border border-ui-border/30 rounded-lg hover:bg-ui-bg/60 transition">
                    <div>
                        <p class="font-semibold text-ui-fg">#{{ $order->order_number }}</p>
                        <p class="text-xs text-ui-muted">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-ui-bg/60 border
                            @switch($order->status)
                                @case('pending') border-ui-border/40 text-ui-muted @break
                                @case('processing') border-ui-accent text-ui-accent @break
                                @case('completed') border-ui-accent text-ui-accent @break
                                @default border-ui-accent2 text-ui-accent2
                            @endswitch">
                            @switch($order->status)
                                @case('pending') Очікується @break
                                @case('processing') В обробці @break
                                @case('completed') Завершено @break
                                @default Скасовано
                            @endswitch
                        </span>
                        <span class="font-bold text-ui-fg">{{ number_format($order->total_amount, 2, '.', ' ') }} ₴</span>
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-ui-accent hover:brightness-110">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($user->orders->count() > 5)
            <a href="{{ route('admin.orders.index') }}?user={{ $user->id }}" class="inline-flex items-center text-ui-accent hover:brightness-110 mt-3">
                <i class="fas fa-arrow-right mr-1"></i> Переглянути всі замовлення
            </a>
            @endif
        </div>
        @endif
    </div>

    <!-- Сайдбар -->
    <div class="space-y-6">
        <!-- Інформація про користувача -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4">Інформація</h3>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-ui-muted mb-1">ID</p>
                    <p class="font-mono text-ui-fg">{{ $user->id }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Email</p>
                    <p class="text-ui-fg break-all">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Роль</p>
                    <p class="text-ui-fg">{{ $user->role === 'admin' ? 'Адміністратор' : 'Користувач' }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Зареєстрований</p>
                    <p class="text-ui-fg">{{ $user->created_at->format('d.m.Y') }}</p>
                </div>
                <div>
                    <p class="text-ui-muted mb-1">Останній вхід</p>
                    <p class="text-ui-fg">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Дії -->
        <div class="space-y-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="block w-full text-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
                <i class="fas fa-edit mr-2"></i> Редагувати
            </a>
            <a href="{{ route('admin.users.index') }}" class="block w-full text-center px-4 py-2 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Повернутися
            </a>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Видалити цього користувача? Це незворотна дія.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-ui-accent2 text-ui-bg rounded-lg hover:brightness-110 transition font-medium">
                    <i class="fas fa-trash mr-2"></i> Видалити
                </button>
            </form>
            @else
            <button disabled class="w-full px-4 py-2 bg-ui-border/40 text-ui-muted rounded-lg cursor-not-allowed font-medium" title="Ви не можете видалити себе">
                <i class="fas fa-trash mr-2"></i> Видалити
            </button>
            @endif
        </div>
    </div>
</div>
@endsection
