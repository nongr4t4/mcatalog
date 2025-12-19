<x-app-layout>
    <div class="bg-ui-bg min-h-screen py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-ui-fg mb-2">
                    <i class="fas fa-shopping-cart text-ui-accent mr-3"></i>Ваш кошик
                </h1>
                <p class="text-ui-muted">{{ $cartItems->count() }} товар(и) у кошику</p>
            </div>

            @if($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Товари -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                        @php
                            $mainPhoto = $item->product->photos->where('is_main', true)->first();
                        @endphp
                        <div class="bg-ui-bg rounded-lg border border-ui-border/40 p-6 shadow-lg shadow-black/40">
                            <div class="flex gap-6">
                                <!-- Фото товару -->
                                <div class="w-24 h-24 flex-shrink-0">
                                    @if($mainPhoto)
                                    <img src="{{ Storage::url($mainPhoto->path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg border border-ui-border/40">
                                    @else
                                    <div class="w-full h-full bg-ui-bg border border-ui-border/40 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-ui-border text-2xl"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Інформація -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-ui-fg mb-2">{{ $item->product->name }}</h3>
                                    <p class="text-ui-accent font-bold text-xl mb-4">{{ number_format($item->product->price, 2, '.', ' ') }} ₴</p>
                                    
                                    <!-- Управління кількістю -->
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-3">
                                        @csrf
                                        <div class="flex items-center border border-ui-border/40 rounded-lg bg-ui-bg">
                                            <button type="button" onclick="decreaseQuantity(this)" class="px-3 py-2 text-ui-fg hover:bg-ui-panel/40">−</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ max($item->product->stock, $item->quantity) }}" class="w-16 text-center border-0 py-2 font-semibold bg-transparent text-ui-fg focus:ring-0" onchange="this.form.submit()">
                                            <button type="button" onclick="increaseQuantity(this)" class="px-3 py-2 text-ui-fg hover:bg-ui-panel/40">+</button>
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition text-sm font-medium hidden" id="updateBtn">Оновити</button>
                                    </form>
                                </div>

                                <!-- Сума та видалення -->
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-ui-fg mb-4">{{ number_format($item->product->price * $item->quantity, 2, '.', ' ') }} ₴</p>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-ui-accent2 hover:brightness-110 font-medium text-sm flex items-center justify-end">
                                            <i class="fas fa-trash mr-1"></i>Видалити
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Сайдбар - Підсумок -->
                    <div class="lg:col-span-1">
                        <div class="bg-ui-bg rounded-lg border border-ui-border/40 p-6 sticky top-20 shadow-xl shadow-black/50">
                            <h2 class="text-2xl font-bold text-ui-fg mb-6">Підсумок замовлення</h2>
                            
                            <div class="space-y-4 mb-6 pb-6 border-b border-ui-border/40">
                                <div class="flex justify-between text-ui-muted">
                                    <span>Кількість товарів:</span>
                                    <span class="font-semibold text-ui-fg">{{ $cartItems->sum('quantity') }} шт</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-ui-fg">
                                    <span>Сума:</span>
                                    <span class="text-ui-accent">{{ number_format($total, 2, '.', ' ') }} ₴</span>
                                </div>
                            </div>

                            <a href="{{ route('checkout.create') }}" class="w-full block text-center px-6 py-4 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition font-bold text-lg mb-3">
                                <i class="fas fa-check-circle mr-2"></i>Оформити замовлення
                            </a>

                            <a href="{{ route('catalog.index') }}" class="w-full block text-center px-6 py-3 border border-ui-border/40 text-ui-fg rounded-lg hover:bg-ui-bg/40 transition font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>Продовжити покупки
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16 bg-ui-bg rounded-lg border border-ui-border/40 shadow-lg shadow-black/40">
                    <i class="fas fa-shopping-cart text-6xl text-ui-border mb-4 block"></i>
                    <h2 class="text-2xl font-bold text-ui-fg mb-2">Ваш кошик порожній</h2>
                    <p class="text-ui-muted mb-6">Додайте товари з каталогу</p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition font-medium">
                        <i class="fas fa-arrow-right mr-2"></i>Перейти до каталогу
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
    // Глобальні функції ініціалізуються в app.js через initGlobalHelpers()
    </script>
</x-app-layout>