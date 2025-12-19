@extends('layouts.admin')

@section('title', 'Замовлення #' . $order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Основна інформація -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Інформація про замовлення -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h2 class="text-xl font-bold text-ui-fg mb-4 flex items-center">
                <i class="fas fa-shopping-cart mr-3 text-ui-accent"></i>
                Деталі замовлення
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-ui-muted mb-1">Номер замовлення</p>
                    <p class="text-lg font-semibold text-ui-fg">#{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-ui-muted mb-1">Статус</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-ui-bg/60 border
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
                </div>
                <div>
                    <p class="text-sm text-ui-muted mb-1">Дата</p>
                    <p class="text-ui-fg">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-ui-muted mb-1">Остання зміна</p>
                    <p class="text-ui-fg">{{ $order->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-ui-border/40">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-ui-muted mb-1">Адреса доставки</p>
                        <p class="text-ui-fg">{{ $order->shipping_address }}</p>
                    </div>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-110 transition">
                        <i class="fas fa-edit mr-2"></i> Редагувати статус
                    </a>
                </div>
            </div>
        </div>

        <!-- Товари в замовленні -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h2 class="text-xl font-bold text-ui-fg mb-4 flex items-center">
                <i class="fas fa-box mr-3 text-ui-accent"></i>
                Товари ({{ $order->items->count() }})
            </h2>

            <div class="space-y-3">
                @foreach($order->items as $item)
                @php
                    $mainPhoto = $item->product->photos->where('is_main', true)->first();
                @endphp
                <div class="flex items-center justify-between p-4 bg-ui-bg/40 border border-ui-border/30 rounded-lg hover:bg-ui-bg/60 transition">
                    <div class="flex items-center space-x-4 flex-1">
                        @if($mainPhoto)
                        <img src="{{ Storage::url($mainPhoto->path) }}" alt="Фото" class="w-16 h-16 object-cover rounded-lg border border-ui-border/40">
                        @else
                        <div class="w-16 h-16 bg-ui-bg/60 rounded-lg border border-ui-border/40 flex items-center justify-center">
                            <i class="fas fa-image text-ui-border text-xl"></i>
                        </div>
                        @endif
                        <div>
                            <a href="{{ route('catalog.show', ['product' => $item->product_id]) }}" target="_blank" class="font-semibold text-ui-accent hover:brightness-110">
                                {{ $item->product->name }}
                            </a>
                            <p class="text-sm text-ui-muted">Кількість: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-ui-fg">{{ number_format($item->product_price * $item->quantity, 2, '.', ' ') }} ₴</p>
                        <p class="text-xs text-ui-muted">{{ number_format($item->product_price, 2, '.', ' ') }} ₴ / шт</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Сайдбар -->
    <div class="space-y-6">
        <!-- Інформація про клієнта -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4 flex items-center">
                <i class="fas fa-user mr-2 text-ui-accent"></i>
                Клієнт
            </h3>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-ui-muted mb-1">Ім'я</p>
                    <p class="font-semibold text-ui-fg">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-ui-muted mb-1">Email</p>
                    <p class="text-ui-fg break-all">{{ $order->user->email }}</p>
                </div>
                <a href="{{ route('admin.users.edit', $order->user) }}" class="inline-flex items-center text-ui-accent hover:brightness-110 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i> Переглянути профіль
                </a>
            </div>
        </div>

        <!-- Сума замовлення -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-6">
            <h3 class="text-lg font-bold text-ui-fg mb-4">Сума замовлення</h3>

            <div class="space-y-2 text-ui-muted">
                <div class="flex justify-between">
                    <span>Підсумок:</span>
                    <span>{{ number_format($order->total_amount, 2, '.', ' ') }} ₴</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-ui-border/40">
                <div class="flex justify-between font-bold text-lg text-ui-fg">
                    <span>Всього:</span>
                    <span class="text-ui-accent">{{ number_format($order->total_amount, 2, '.', ' ') }} ₴</span>
                </div>
            </div>
        </div>

        <!-- Дії -->
        <div class="flex flex-col gap-2">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-ui-border/40 rounded-lg text-ui-fg font-medium hover:bg-ui-bg/40 transition">
                <i class="fas fa-arrow-left mr-2"></i> Повернутися
            </a>
        </div>
    </div>
</div>
@endsection
