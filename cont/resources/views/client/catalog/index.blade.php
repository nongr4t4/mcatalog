<x-app-layout>
    <div class="bg-ui-bg py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-ui-fg mb-2">Каталог товарів</h1>
                <p class="text-ui-muted">Знайдіть саме те, що вам потрібно</p>
            </div>

            <!-- Форма фільтрів -->
            <form action="{{ route('catalog.index') }}" method="GET" id="filterForm">
                <!-- Пошук та сортування -->
                <div class="mb-6 bg-ui-bg rounded-lg border border-ui-border/40 p-4 shadow-lg shadow-black/40">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Пошук -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <i class="fas fa-search text-ui-border"></i>
                                </div>
                                <input type="text" name="search" placeholder="Пошук за назвою або описом товару..." 
                                       value="{{ request('search') }}"
                                                    class="w-full pl-10 pr-4 py-3 rounded-lg bg-ui-bg text-ui-fg border border-ui-border/40 placeholder-ui-muted/70 focus:ring-2 focus:ring-ui-accent focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Категорія (dropdown) -->
                        <div class="md:w-48">
                            <select name="category" class="w-full py-3 px-4 rounded-lg bg-ui-bg text-ui-fg border border-ui-border/40 focus:ring-2 focus:ring-ui-accent focus:border-transparent" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Всі категорії</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string)request('category') === (string)$category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Сортування -->
                        <div class="md:w-48">
                            <select name="sort" class="w-full py-3 px-4 rounded-lg bg-ui-bg text-ui-fg border border-ui-border/40 focus:ring-2 focus:ring-ui-accent focus:border-transparent" onchange="document.getElementById('filterForm').submit()">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Спочатку нові</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Ціна: від низької</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Ціна: від високої</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Назва: А-Я</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Назва: Я-А</option>
                            </select>
                        </div>
                    </div>

                    <!-- Фільтри за ціною та рейтингом -->
                    <div class="mt-4 pt-4 border-t border-ui-border/40">
                        <div class="flex flex-col lg:flex-row items-center gap-4">
                            <span class="text-sm font-medium text-ui-muted whitespace-nowrap">
                                <i class="fas fa-hryvnia-sign mr-1 text-ui-accent"></i>Ціна:
                            </span>
                            <div class="flex items-center gap-2 flex-1">
                                <input type="number" name="price_from" placeholder="Від {{ $priceRange->min_price ?? 0 }}" 
                                       value="{{ request('price_from') }}"
                                       min="0" step="0.01"
                                        class="w-full sm:w-32 px-3 py-2 rounded-lg bg-ui-bg text-ui-fg border border-ui-border/40 placeholder-ui-muted/70 focus:ring-2 focus:ring-ui-accent focus:border-transparent text-sm">
                                    <span class="text-ui-border">—</span>
                                <input type="number" name="price_to" placeholder="До {{ $priceRange->max_price ?? 99999 }}" 
                                       value="{{ request('price_to') }}"
                                       min="0" step="0.01"
                                        class="w-full sm:w-32 px-3 py-2 rounded-lg bg-ui-bg text-ui-fg border border-ui-border/40 placeholder-ui-muted/70 focus:ring-2 focus:ring-ui-accent focus:border-transparent text-sm">
                                    <span class="text-ui-muted text-sm">₴</span>
                            </div>
                            <div class="w-full lg:w-48">
                                <select name="stars" class="w-full py-2.5 px-4 rounded-lg bg-ui-bg text-ui-fg border border-ui-border/40 focus:ring-2 focus:ring-ui-accent focus:border-transparent">
                                    @foreach([0=>'Будь-який рейтинг',5=>'5 зірок',4=>'4+ зірки',3=>'3+ зірки',2=>'2+ зірки',1=>'1+ зірка'] as $val=>$label)
                                        <option value="{{ $val }}" {{ (string)request('stars', '0') === (string)$val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition font-medium text-sm">
                                    <i class="fas fa-filter mr-1"></i>Застосувати
                                </button>
                                @if(request('search') || request('category') || request('price_from') || request('price_to') || request('sort') || request('stars'))
                                <a href="{{ route('catalog.index') }}" class="px-4 py-2 bg-ui-bg text-ui-fg border border-ui-border/40 rounded-lg hover:bg-ui-panel/40 transition font-medium text-sm">
                                    <i class="fas fa-times mr-1"></i>Скинути
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Активні фільтри -->
                    @if(request('search') || request('category') || request('price_from') || request('price_to') || request('stars'))
                    <div class="mt-4 pt-4 border-t border-ui-border/40">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-ui-muted">Активні фільтри:</span>
                            @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-ui-bg border border-ui-border/40 text-ui-fg">
                                <i class="fas fa-search mr-1"></i>{{ request('search') }}
                            </span>
                            @endif
                            @if(request('category'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-ui-bg border border-ui-border/40 text-ui-fg">
                                <i class="fas fa-tag mr-1"></i>{{ $categories->firstWhere('id', (int) request('category'))?->name }}
                            </span>
                            @endif
                            @if(request('price_from') || request('price_to'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-ui-bg border border-ui-border/40 text-ui-fg">
                                <i class="fas fa-hryvnia-sign mr-1"></i>
                                {{ request('price_from') ?: '0' }} - {{ request('price_to') ?: 'без обмежень' }} ₴
                            </span>
                            @endif
                            @if(request('stars') && (int) request('stars') > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-ui-bg border border-ui-border/40 text-ui-fg">
                                <i class="fas fa-star mr-1"></i>{{ request('stars') }}+ зірок
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </form>

            <!-- Результати -->
            <div class="mb-4 flex items-center justify-between">
                <p class="text-ui-muted">
                    Знайдено: <span class="font-semibold text-ui-fg">{{ $products->total() }}</span> товарів
                </p>
            </div>

            <!-- Товари -->
            @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                @php
                    $mainPhoto = $product->photos->where('is_main', true)->first();
                @endphp
                <div class="group bg-ui-bg rounded-lg border border-ui-border/40 hover:border-ui-accent/60 transition overflow-hidden flex flex-col shadow-xl shadow-black/50">
                    <!-- Зображення (клікабельна для модалі) -->
                    <div class="relative h-56 bg-ui-bg overflow-hidden cursor-pointer"
                         onclick="window.location='{{ url('/catalog/' . $product->id) }}'">
                        @if($mainPhoto)
                        <img src="{{ Storage::url($mainPhoto->path) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-ui-bg">
                            <i class="fas fa-image text-ui-border text-4xl"></i>
                        </div>
                        @endif
                        
                        <!-- Статус наявності -->
                        <div class="absolute top-3 right-3">
                            @if($product->stock > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-ui-bg text-ui-fg border border-ui-border/40">
                                <i class="fas fa-check-circle mr-1"></i>В наявності
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-ui-bg text-ui-muted border border-ui-border/40">
                                <i class="fas fa-ban mr-1"></i>Немає
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Інформація -->
                    <div class="p-4 flex-1 flex flex-col">
                        <a href="{{ route('catalog.show', $product) }}" class="text-base font-semibold text-ui-fg mb-3 line-clamp-2 cursor-pointer hover:text-ui-accent transition">
                            {{ $product->name }}
                        </a>

                        <a href="{{ route('catalog.show', $product) }}#reviews" class="flex items-center gap-2 text-sm mb-3 group" aria-label="Переглянути відгуки">
                            @php
                                $avg = $product->average_rating;
                                $full = floor($avg);
                                $half = ($avg - $full) >= 0.5;
                            @endphp
                            <div class="flex items-center text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $full)
                                        <i class="fas fa-star"></i>
                                    @elseif($half && $i === $full + 1)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star text-ui-border"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-ui-fg font-semibold group-hover:text-ui-accent">{{ number_format($avg, 1) }}</span>
                            <span class="text-ui-muted group-hover:text-ui-accent">({{ $product->ratings_count }} відгуків)</span>
                        </a>

                        <!-- Ціна та кошик -->
                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-xl font-bold text-ui-accent">
                                {{ number_format($product->price, 2, '.', ' ') }} ₴
                            </span>
                            @if($product->stock > 0)
                            <button type="button" data-add-to-cart="{{ $product->id }}" class="p-2.5 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition flex items-center" title="Додати до кошика">
                                <i class="fas fa-shopping-cart text-lg"></i>
                            </button>
                            @else
                            <button disabled class="p-2.5 bg-ui-bg text-ui-border rounded-lg cursor-not-allowed flex items-center border border-ui-border/30">
                                <i class="fas fa-shopping-cart text-lg"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Пагінація -->
            <div class="mt-12">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-16 bg-ui-bg rounded-lg border border-ui-border/40 shadow-lg shadow-black/40">
                <i class="fas fa-box-open text-6xl text-ui-border mb-4"></i>
                <h3 class="text-2xl font-semibold text-ui-fg mb-2">Товарів не знайдено</h3>
                <p class="text-ui-muted mb-6">Спробуйте змінити критерії пошуку або фільтрації</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-ui-accent text-ui-bg rounded-lg hover:brightness-95 transition">
                    <i class="fas fa-redo mr-2"></i>Очистити фільтри
                </a>
            </div>
            @endif
        </div>
    </div>

</x-app-layout>