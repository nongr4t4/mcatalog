@extends('layouts.admin')

@section('title', 'Замовлення')

@section('content')
<div class="bg-ui-bg border border-ui-border/40 rounded-lg shadow-xl shadow-black/50 overflow-hidden">
    @if(isset($filterUser) && $filterUser)
        <div class="px-6 py-3 bg-ui-bg/60 border-b border-ui-border/40 flex items-center justify-between">
            <div class="text-sm text-ui-fg">
                Показано замовлення користувача <span class="font-semibold">{{ $filterUser->name }}</span>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-ui-accent hover:brightness-110 font-medium">Скинути фільтр</a>
        </div>
    @endif
    @if($orders->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-ui-bg/60 border-b border-ui-border/40">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Номер</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Клієнт</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Сума</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Статус</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-ui-fg">Дата</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-ui-fg">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ui-border/30">
                @foreach($orders as $order)
                    <tr class="hover:bg-ui-bg/40 transition">
                        <td class="px-6 py-4 text-sm font-medium text-ui-fg">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-ui-accent hover:brightness-110">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-ui-fg">{{ number_format($order->total_amount, 2, '.', ' ') }} ₴</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-ui-bg/60 border
                                @if($order->status == 'pending') border-ui-border/40 text-ui-muted
                                @elseif($order->status == 'processing') border-ui-accent text-ui-accent
                                @elseif($order->status == 'completed') border-ui-accent text-ui-accent
                                @else border-ui-accent2 text-ui-accent2 @endif">
                                @switch($order->status)
                                    @case('pending')
                                        Очікується
                                        @break
                                    @case('processing')
                                        В обробці
                                        @break
                                    @case('completed')
                                        Завершено
                                        @break
                                    @default
                                        Скасовано
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-ui-muted">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center text-ui-accent hover:brightness-110 transition" title="Переглянути">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center text-ui-accent2 hover:brightness-110 transition" title="Редагувати статус">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="bg-ui-bg/60 px-6 py-4 border-t border-ui-border/40">
        {{ $orders->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-shopping-cart text-ui-border text-4xl mb-4"></i>
        <p class="text-ui-muted font-medium">Замовлень не знайдено</p>
    </div>
    @endif
</div>
@endsection
