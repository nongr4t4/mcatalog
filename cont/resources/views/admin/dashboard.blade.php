@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
    <!-- Карточки статистики -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Дохід -->
        <div class="bg-ui-bg border border-ui-border/40 p-6 rounded-lg transition shadow-lg shadow-black/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-ui-muted font-medium mb-1">Загальний дохід</p>
                    <h3 class="text-3xl font-bold text-ui-fg">{{ number_format($stats['revenue'], 0, '.', ' ') }} ₴</h3>
                </div>
                <div class="p-3 rounded-full bg-ui-bg/60 border border-ui-border/40 text-ui-accent text-2xl">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <!-- Замовлення -->
        <div class="bg-ui-bg border border-ui-border/40 p-6 rounded-lg transition shadow-lg shadow-black/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-ui-muted font-medium mb-1">Всього замовлень</p>
                    <h3 class="text-3xl font-bold text-ui-fg">{{ $stats['orders_count'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-ui-bg/60 border border-ui-border/40 text-ui-accent text-2xl">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Клієнти -->
        <div class="bg-ui-bg border border-ui-border/40 p-6 rounded-lg transition shadow-lg shadow-black/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-ui-muted font-medium mb-1">Клієнтів</p>
                    <h3 class="text-3xl font-bold text-ui-fg">{{ $stats['clients_count'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-ui-bg/60 border border-ui-border/40 text-ui-accent2 text-2xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Товари -->
        <div class="bg-ui-bg border border-ui-border/40 p-6 rounded-lg transition shadow-lg shadow-black/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-ui-muted font-medium mb-1">Товарів каталогу</p>
                    <h3 class="text-3xl font-bold text-ui-fg">{{ $stats['products_count'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-ui-bg/60 border border-ui-border/40 text-ui-accent text-2xl">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Графік та швидкі дії -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Графік -->
        <div class="lg:col-span-2 bg-ui-bg border border-ui-border/40 rounded-lg p-6 shadow-xl shadow-black/50">
            <h2 class="text-xl font-bold text-ui-fg mb-4">Динаміка продажів (30 днів)</h2>
            <div class="relative h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Швидкі дії -->
        <div class="bg-ui-bg border border-ui-border/40 rounded-lg p-6 shadow-xl shadow-black/50">
            <h2 class="text-xl font-bold text-ui-fg mb-4">Швидкі дії</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.products.create') }}" class="flex items-center justify-center p-3 bg-ui-bg/40 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/60 transition font-medium">
                    <i class="fas fa-plus mr-2"></i> Додати товар
                </a>
                <a href="{{ route('admin.categories.create') }}" class="flex items-center justify-center p-3 bg-ui-bg/40 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/60 transition font-medium">
                    <i class="fas fa-tags mr-2"></i> Створити категорію
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-center p-3 bg-ui-bg/40 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/60 transition font-medium">
                    <i class="fas fa-list mr-2"></i> Всі замовлення
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center justify-center p-3 bg-ui-bg/40 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/60 transition font-medium">
                    <i class="fas fa-boxes mr-2"></i> Всі товари
                </a>
            </div>
        </div>
    </div>

    <!-- Останні замовлення -->
    <div class="bg-ui-bg border border-ui-border/40 rounded-lg overflow-hidden shadow-xl shadow-black/50">
        <div class="px-6 py-4 border-b border-ui-border/40 bg-ui-bg/60">
            <h2 class="text-xl font-bold text-ui-fg">Останні замовлення</h2>
        </div>
        
        @if($stats['recent_orders']->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-ui-bg/60 border-b border-ui-border/40">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-ui-fg uppercase">Номер</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-ui-fg uppercase">Клієнт</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-ui-fg uppercase">Сума</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-ui-fg uppercase">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-ui-fg uppercase">Дата</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-ui-fg uppercase">Дія</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ui-border/30">
                    @foreach($stats['recent_orders'] as $order)
                        <tr class="hover:bg-ui-bg/40 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-ui-fg">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-ui-accent hover:brightness-110">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-ui-muted">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-ui-fg">
                                {{ number_format($order->total_amount, 2, '.', ' ') }} ₴
                            </td>
                            <td class="px-6 py-4 text-sm">
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
                            </td>
                            <td class="px-6 py-4 text-sm text-ui-muted">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-ui-accent hover:brightness-110 font-medium">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 bg-ui-bg/40">
            <i class="fas fa-inbox text-ui-border text-4xl mb-4 block"></i>
            <p class="text-ui-muted font-medium">Замовлень поки немає</p>
        </div>
        @endif

        <div class="bg-ui-bg/60 px-6 py-4 border-t border-ui-border/40 text-right">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-ui-accent hover:brightness-110 font-medium">
                Переглянути всі замовлення <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Графік (Chart.js): використовується лише на дашборді -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Ініціалізація графіка продажів
    document.addEventListener('DOMContentLoaded', () => {
        const chartLabels = @json($chartLabels);
        const chartValues = @json($chartValues);
        window.initDashboardChart(chartLabels, chartValues);
    });
    </script>
@endsection