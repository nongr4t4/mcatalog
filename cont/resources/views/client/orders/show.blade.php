<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Замовлення #{{ $order->order_number }}</h1>
                <a href="{{ route('my-orders.index') }}" class="text-ui-accent hover:underline">Назад до списку</a>
            </div>

            <div class="bg-ui-bg border border-ui-border/40 overflow-hidden sm:rounded-lg mb-6 shadow-lg shadow-black/40">
                <div class="px-6 py-5 border-b border-ui-border/40">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-ui-muted">Статус</dt>
                            <dd class="mt-1 text-sm text-ui-fg font-semibold">{{ $order->status_label }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-ui-muted">Дата створення</dt>
                            <dd class="mt-1 text-sm text-ui-fg">{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-ui-muted">Адреса доставки</dt>
                            <dd class="mt-1 text-sm text-ui-fg">{{ $order->shipping_address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-ui-bg border border-ui-border/40 overflow-hidden sm:rounded-lg shadow-xl shadow-black/50">
                <table class="min-w-full divide-y divide-ui-border/40">
                    <thead class="bg-ui-bg">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-ui-muted uppercase">Товар</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-ui-muted uppercase">Ціна</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-ui-muted uppercase">К-сть</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-ui-muted uppercase">Сума</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ui-border/40">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-ui-fg">{{ $item->product?->name ?? ('Товар #' . $item->product_id) }}</td>
                                <td class="px-6 py-4 text-sm text-ui-muted">{{ $item->formatted_product_price }}</td>
                                <td class="px-6 py-4 text-sm text-ui-muted">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-ui-fg">{{ $item->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-ui-bg">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold">Разом:</td>
                            <td class="px-6 py-4 font-bold text-lg text-ui-accent">{{ $order->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div id="order-reviews" class="mt-8 bg-ui-bg border border-ui-border/40 sm:rounded-lg shadow-xl shadow-black/50">
                <div class="px-6 py-5 border-b border-ui-border/40 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-ui-fg">Ваші відгуки по цьому замовленню</h3>
                        <p class="text-sm text-ui-muted">Один відгук на товар. Можна змінити або видалити.</p>
                    </div>
                </div>
                <div class="divide-y divide-ui-border/40">
                    @foreach($order->items as $item)
                        @php $review = $reviewsByProduct[$item->product_id] ?? null; @endphp
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-semibold text-ui-fg">{{ $item->product?->name ?? ('Товар #' . $item->product_id) }}</div>
                                <a href="{{ route('catalog.show', ['product' => $item->product_id]) }}#reviews" class="text-xs text-ui-accent hover:underline">Переглянути у каталозі</a>
                            </div>

                            @if($review)
                                <div class="flex items-center gap-2 text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->stars ? 'fas fa-star' : 'far fa-star text-ui-border' }}"></i>
                                    @endfor
                                    <span class="text-sm text-ui-fg">{{ $review->stars }}/5</span>
                                </div>
                                @if($review->comment)
                                    <p class="text-ui-fg">{{ $review->comment }}</p>
                                @endif
                                <div class="text-xs text-ui-muted">Оновлено: {{ $review->updated_at->format('d.m.Y H:i') }}</div>

                                <div class="flex flex-wrap gap-3 items-center">
                                    <details class="w-full md:w-auto">
                                        <summary class="cursor-pointer inline-flex items-center px-3 py-2 border border-ui-border/40 rounded-lg text-sm text-ui-fg hover:border-ui-accent/60">Редагувати</summary>
                                        <div class="mt-3 bg-ui-bg/60 border border-ui-border/40 rounded-lg p-4">
                                            <form method="POST" action="{{ route('catalog.reviews.update', [$item->product_id, $review->id]) }}" class="space-y-3">
                                                @csrf
                                                @method('PATCH')
                                                <div class="flex flex-wrap gap-2">
                                                    @for($i = 5; $i >= 0; $i--)
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="stars" value="{{ $i }}" class="sr-only" {{ $review->stars === $i ? 'checked' : '' }}>
                                                            <span class="px-3 py-2 rounded-lg border text-sm font-medium inline-flex items-center gap-2 {{ $review->stars === $i ? 'border-ui-accent bg-ui-bg text-ui-fg' : 'border-ui-border/40 text-ui-muted hover:border-ui-accent/60 hover:text-ui-fg' }}">
                                                                <i class="fas fa-star text-amber-500"></i>{{ $i }}
                                                            </span>
                                                        </label>
                                                    @endfor
                                                </div>
                                                <textarea name="comment" rows="3" class="w-full rounded-lg bg-ui-bg/60 text-ui-fg border-ui-border/40 focus:border-ui-accent focus:ring-ui-accent/40" placeholder="Коментар (необов'язково)">{{ $review->comment }}</textarea>
                                                <div class="flex gap-2 justify-end">
                                                    <button type="submit" class="px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 text-sm">Зберегти</button>
                                                </div>
                                            </form>
                                        </div>
                                    </details>
                                    <form method="POST" action="{{ route('catalog.reviews.destroy', [$item->product_id, $review->id]) }}" onsubmit="return confirm('Видалити відгук?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 text-sm text-ui-accent2 border border-ui-accent2/50 rounded-lg hover:bg-ui-accent2/10">Видалити</button>
                                    </form>
                                </div>
                            @else
                                <form method="POST" action="{{ route('catalog.reviews.store', $item->product_id) }}" class="space-y-3 bg-ui-bg/60 border border-ui-border/40 rounded-lg p-4">
                                    @csrf
                                    <div class="flex flex-wrap gap-2">
                                        @for($i = 5; $i >= 0; $i--)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="stars" value="{{ $i }}" class="sr-only" {{ $i === 5 ? 'checked' : '' }}>
                                                <span class="px-3 py-2 rounded-lg border text-sm font-medium inline-flex items-center gap-2 {{ $i === 5 ? 'border-ui-accent bg-ui-bg text-ui-fg' : 'border-ui-border/40 text-ui-muted hover:border-ui-accent/60 hover:text-ui-fg' }}">
                                                    <i class="fas fa-star text-amber-500"></i>{{ $i }}
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                    <textarea name="comment" rows="3" class="w-full rounded-lg bg-ui-bg/60 text-ui-fg border-ui-border/40 focus:border-ui-accent focus:ring-ui-accent/40" placeholder="Коментар (необов'язково)"></textarea>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 text-sm">Залишити відгук</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>