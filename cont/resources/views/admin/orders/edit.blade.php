@extends('layouts.admin')

@section('title', 'Редагувати замовлення #' . $order->order_number)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-8">
        <div class="mb-6 pb-6 border-b border-ui-border/40">
            <h1 class="text-2xl font-bold text-ui-fg mb-2">Замовлення #{{ $order->order_number }}</h1>
            <p class="text-ui-muted">Клієнт: <strong class="text-ui-fg">{{ $order->user->name }}</strong> ({{ $order->user->email }})</p>
        </div>

        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Статус -->
            <div>
                <label for="status" class="block text-sm font-semibold text-ui-fg mb-2">Статус замовлення *</label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 bg-ui-bg/60 text-ui-fg border @error('status') border-ui-accent2 @else border-ui-border/40 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-ui-accent/40 focus:border-ui-accent transition">
                    <option value="">-- Виберіть статус --</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Поточний статус інформація -->
            <div class="bg-ui-bg/60 border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-4">
                <p class="text-sm text-ui-fg">
                    <strong>Поточний статус:</strong>
                    <span class="inline-block ml-2 px-3 py-1 rounded-full text-sm font-semibold bg-ui-bg/60 border
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
                </p>
            </div>

            <!-- Інформація про товари -->
            <div class="bg-ui-bg/40 border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-4">
                <h3 class="font-semibold text-ui-fg mb-3">Товари в замовленні:</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm text-ui-muted">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span class="font-medium text-ui-fg">{{ number_format($item->product_price * $item->quantity, 2, '.', ' ') }} ₴</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 pt-3 border-t border-ui-border/40 flex justify-between font-bold text-ui-fg">
                    <span>Всього:</span>
                    <span>{{ number_format($order->total_amount, 2, '.', ' ') }} ₴</span>
                </div>
            </div>

            <!-- Адреса доставки -->
            <div class="bg-ui-bg/40 border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 p-4">
                <h3 class="font-semibold text-ui-fg mb-2">Адреса доставки:</h3>
                <p class="text-ui-muted">{{ $order->shipping_address }}</p>
            </div>

            <!-- Кнопки дії -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-ui-border/40">
                <a href="{{ route('admin.orders.show', $order) }}"
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
