<x-app-layout>
    <div class="bg-ui-bg min-h-screen py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-ui-fg mb-2">
                    <i class="fas fa-credit-card text-ui-accent mr-3"></i>Оформлення замовлення
                </h1>
                <p class="text-ui-muted">Заповніть деталі для доставки вашого замовлення</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Форма -->
                <div class="lg:col-span-2">
                    <div class="bg-ui-bg rounded-lg border border-ui-border/40 p-8 shadow-xl shadow-black/50">
                        <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Адреса доставки -->
                            <div>
                                <label for="shipping_address" class="block text-sm font-semibold text-ui-muted mb-2">
                                    <i class="fas fa-map-marker-alt text-ui-accent mr-2"></i>Адреса доставки *
                                </label>
                                <textarea 
                                    name="shipping_address" 
                                    id="shipping_address"
                                    required 
                                    rows="4"
                                    class="w-full px-4 py-3 bg-ui-bg text-ui-fg border border-ui-border/40 rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition @error('shipping_address') border-ui-accent2 @enderror"
                                    placeholder="Місто, вулиця, номер будинку, квартира, відділення пошти...">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Примітки -->
                            <div>
                                <label for="notes" class="block text-sm font-semibold text-ui-muted mb-2">
                                    <i class="fas fa-sticky-note text-ui-accent mr-2"></i>Примітки (необов'язково)
                                </label>
                                <textarea 
                                    name="notes" 
                                    id="notes"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-ui-bg text-ui-fg border border-ui-border/40 rounded-lg focus:ring-2 focus:ring-ui-accent focus:border-transparent transition @error('notes') border-ui-accent2 @enderror"
                                    placeholder="Будь-які спеціальні інструкції для доставки...">{{ old('notes') }}</textarea>
                                @error('notes')
                                <span class="text-ui-accent2 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Кнопка подачі -->
                            <div class="pt-6 border-t border-ui-border/40">
                                <button type="submit" class="w-full px-6 py-4 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition font-bold text-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-2"></i>Підтвердити замовлення
                                </button>
                                <a href="{{ route('cart.index') }}" class="w-full block text-center mt-3 px-6 py-3 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i>Повернутися в кошик
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Сайдбар - Підсумок -->
                <div class="lg:col-span-1">
                    <div class="bg-ui-bg rounded-lg border border-ui-border/40 p-6 sticky top-20 shadow-xl shadow-black/50">
                        <h2 class="text-2xl font-bold text-ui-fg mb-6 flex items-center">
                            <i class="fas fa-receipt text-ui-accent mr-2"></i>Ваше замовлення
                        </h2>

                        <!-- Товари -->
                        <div class="space-y-4 mb-6 pb-6 border-b border-ui-border/40 max-h-96 overflow-y-auto scrollbar-thin">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <div>
                                    <p class="font-medium text-ui-fg">{{ $item->product->name }}</p>
                                    <p class="text-ui-muted text-xs">× {{ $item->quantity }} шт</p>
                                </div>
                                <span class="font-semibold text-ui-fg">{{ number_format($item->product->price * $item->quantity, 2, '.', ' ') }} ₴</span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Подсумок -->
                        <div class="space-y-3 mb-6 pb-6 border-b border-ui-border/40">
                            <div class="flex justify-between text-ui-muted">
                                <span>Кількість товарів:</span>
                                <span class="font-semibold">{{ $cartItems->sum('quantity') }} шт</span>
                            </div>
                        </div>

                        <!-- Сума до сплати -->
                        <div class="bg-ui-bg rounded-lg p-4 mb-4 border border-ui-border/40">
                            <p class="text-ui-muted text-sm mb-1">Сума до сплати:</p>
                            <p class="text-4xl font-bold text-ui-accent">{{ number_format($total, 2, '.', ' ') }} ₴</p>
                        </div>

                        <div class="flex gap-2 text-xs text-ui-muted p-3 bg-ui-bg rounded-lg border border-ui-border/40">
                            <i class="fas fa-info-circle text-ui-accent flex-shrink-0 mt-0.5"></i>
                            <span>Натисніть "Підтвердити замовлення" для завершення процесу</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>