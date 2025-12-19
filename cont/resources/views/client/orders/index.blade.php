<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Мої замовлення</h1>

            @if($orders->count() > 0)
                <div class="bg-ui-bg overflow-hidden border border-ui-border/40 sm:rounded-lg shadow-xl shadow-black/50">
                    <table class="w-full text-left">
                        <thead class="bg-ui-bg">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-ui-muted uppercase">Номер</th>
                                <th class="px-6 py-3 text-xs font-medium text-ui-muted uppercase">Дата</th>
                                <th class="px-6 py-3 text-xs font-medium text-ui-muted uppercase">Сума</th>
                                <th class="px-6 py-3 text-xs font-medium text-ui-muted uppercase">Статус</th>
                                <th class="px-6 py-3 text-xs font-medium text-ui-muted uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ui-border/40">
                            @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 font-medium">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $order->formatted_total }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ match($order->status) {
                                                'completed' => 'bg-ui-bg text-ui-accent border border-ui-border/40',
                                                'cancelled' => 'bg-ui-bg text-ui-accent2 border border-ui-border/40',
                                                'pending' => 'bg-ui-bg text-ui-fg border border-ui-border/40',
                                                default => 'bg-ui-bg text-ui-muted border border-ui-border/40'
                                            } }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('my-orders.show', $order) }}" class="text-ui-accent hover:brightness-95">Деталі</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $orders->links() }}</div>
            @else
                <p class="text-ui-muted">У вас ще немає замовлень.</p>
            @endif
        </div>
    </div>
</x-app-layout>