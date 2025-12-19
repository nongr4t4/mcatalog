<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ui-fg leading-tight">{{ $product->name }}</h2>
            <a href="{{ route('catalog.index') }}" class="text-sm text-ui-accent hover:brightness-95">Назад до каталогу</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-ui-bg overflow-hidden border border-ui-border/40 sm:rounded-lg shadow-xl shadow-black/50">
                <div class="p-6 text-ui-fg grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Галерея зображень --}}
                    <div>
                        <div class="relative">
                            @if($product->mainPhoto)
                                <img id="mainImage" src="{{ Storage::url($product->mainPhoto->path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-96 object-cover rounded-lg shadow-md mb-4 transition duration-200" loading="lazy">
                            @else
                                <div class="w-full h-96 bg-ui-bg border border-ui-border/40 rounded-lg flex items-center justify-center text-ui-muted">
                                    Немає зображення
                                </div>
                            @endif
                        </div>

                        @if($product->photos->count() > 0)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($product->photos as $photo)
                                    <button type="button" class="group" onclick="swapMainImage('{{ Storage::url($photo->path) }}')">
                                        <img src="{{ Storage::url($photo->path) }}" 
                                             class="w-full h-20 object-cover rounded border border-ui-border/40 group-hover:border-ui-accent transition" loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Інформація про товар --}}
                    <div>
                        <h1 class="text-3xl font-bold mb-3">{{ $product->name }}</h1>

                        @php
                            $avg = $product->average_rating;
                            $full = floor($avg);
                            $half = ($avg - $full) >= 0.5;
                        @endphp
                        <a href="#reviews" class="flex items-center gap-3 mb-5 group" aria-label="Перейти до відгуків">
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
                            <span class="text-sm font-semibold text-ui-fg group-hover:text-ui-accent">{{ number_format($avg, 1) }} / 5</span>
                            <span class="text-sm text-ui-muted group-hover:text-ui-accent">({{ $product->ratings_count }} відгуків)</span>
                        </a>

                        <div class="text-2xl text-ui-accent font-bold mb-6">
                            {{ $product->formatted_price }}
                        </div>

                        <div class="mb-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-ui-border/40 {{ $product->stock > 0 ? 'text-ui-accent' : 'text-ui-accent2' }}">
                                {{ $product->stock > 0 ? 'В наявності: ' . $product->stock . ' шт.' : 'Немає в наявності' }}
                            </span>
                        </div>

                        <p class="text-ui-fg mb-8 leading-relaxed">
                            {{ $product->description }}
                        </p>

                        @if(!$isAdminPreview && $product->stock > 0)
                            <button type="button" data-add-to-cart="{{ $product->id }}" class="w-full justify-center py-3 text-lg inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg font-semibold rounded-md shadow hover:brightness-95 transition">
                                <i class="fas fa-shopping-cart mr-2"></i>{{ __('Додати в кошик') }}
                            </button>
                        @elseif($isAdminPreview)
                            <p class="text-sm text-ui-muted">Попередній перегляд (кнопка кошика прихована).</p>
                        @endif
                    </div>
                </div>
            </div>

            <div id="reviews" class="mt-8 bg-ui-bg border border-ui-border/40 sm:rounded-lg shadow-xl shadow-black/50">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-ui-fg">Відгуки</h3>
                            <p class="text-sm text-ui-muted">Середня оцінка та досвід покупців</p>
                        </div>
                        @php
                            $avgRating = $product->average_rating;
                            $fullStars = floor($avgRating);
                            $halfStar = ($avgRating - $fullStars) >= 0.5;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex items-center text-amber-500 text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $fullStars)
                                        <i class="fas fa-star"></i>
                                    @elseif($halfStar && $i === $fullStars + 1)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star text-ui-border"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-semibold text-ui-fg">{{ number_format($avgRating, 1) }}/5</div>
                                <div class="text-sm text-ui-muted">{{ $product->ratings_count }} відгуків</div>
                            </div>
                        </div>
                    </div>

                    @auth
                        <form method="POST" action="{{ route('catalog.reviews.store', $product) }}" class="mb-8 bg-ui-bg border border-ui-border/40 rounded-lg p-5">
                            @csrf
                            <div class="flex flex-col gap-4">
                                @if($errors->has('stars') || $errors->has('comment'))
                                    <div class="bg-ui-bg border border-ui-accent2 text-ui-accent2 text-sm rounded-lg px-4 py-2">
                                        @foreach($errors->get('stars', []) as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                        @foreach($errors->get('comment', []) as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <div>
                                    <span class="block text-sm font-semibold text-ui-muted mb-2">Ваша оцінка</span>
                                    <div class="flex flex-wrap gap-2" data-rating-picker>
                                        @for($i = 5; $i >= 1; $i--)
                                            @php $isChecked = (int) old('stars', $userReview->stars ?? 5) === $i; @endphp
                                            <label class="cursor-pointer">
                                                <input type="radio" name="stars" value="{{ $i }}" class="sr-only" {{ $isChecked ? 'checked' : '' }} aria-label="Оцінка {{ $i }} з 5">
                                                <span data-rating-pill class="px-3 py-2 rounded-lg border text-sm font-medium inline-flex items-center gap-2 transition {{ $isChecked ? 'border-ui-accent bg-ui-bg text-ui-fg' : 'border-ui-border/40 text-ui-fg hover:border-ui-accent' }}">
                                                    <i class="fas fa-star text-amber-500"></i>
                                                    {{ $i }}
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <label for="comment" class="block text-sm font-semibold text-ui-muted mb-2">Коментар (необов'язково)</label>
                                    <textarea id="comment" name="comment" rows="4" class="w-full rounded-lg bg-ui-bg text-ui-fg border-ui-border/40 focus:border-ui-accent focus:ring-ui-accent" placeholder="Поділіться враженнями про товар...">{{ old('comment', $userReview->comment ?? '') }}</textarea>
                                </div>

                                <div class="flex justify-end">
                                    <x-primary-button class="inline-flex items-center">
                                        <i class="fas fa-save mr-2"></i>Зберегти відгук
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="mb-8 bg-ui-bg border border-ui-border/40 text-ui-fg px-4 py-3 rounded-lg flex items-center justify-between">
                            <span>Щоб залишити відгук, увійдіть до аккаунта.</span>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-ui-accent text-ui-bg rounded-md hover:brightness-95 transition">
                                <i class="fas fa-sign-in-alt mr-2"></i>Увійти
                            </a>
                        </div>
                    @endauth

                    @if($product->reviews->count() > 0)
                        <div class="relative" data-review-slider>
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm text-ui-muted">Гортайте відгуки або використайте кнопки.</p>
                                <div class="flex items-center gap-2">
                                    <button type="button" data-review-prev class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-ui-border/40 text-ui-fg hover:bg-ui-bg/40" aria-label="Попередній відгук">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button type="button" data-review-next class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-ui-border/40 text-ui-fg hover:bg-ui-bg/40" aria-label="Наступний відгук">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div data-review-track class="-mx-2 px-2 pb-3 flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-thin">
                                @foreach($product->reviews as $review)
                                    <article data-review-card data-review-index="{{ $loop->index }}" class="snap-start shrink-0 w-[85%] sm:w-[420px] bg-ui-bg border border-ui-border/40 rounded-xl p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="text-sm font-semibold text-ui-fg">{{ $review->user->name ?? 'Користувач' }}</div>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <div class="flex items-center text-amber-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="{{ $i <= $review->stars ? 'fas fa-star' : 'far fa-star text-ui-border' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="text-xs text-ui-muted">{{ $review->stars }}/5</span>
                                                </div>
                                            </div>
                                            <span class="text-xs text-ui-border whitespace-nowrap">{{ $review->created_at->format('d.m.Y H:i') }}</span>
                                        </div>

                                        @if($review->comment)
                                            <p class="mt-3 text-ui-fg leading-relaxed">{{ $review->comment }}</p>
                                        @else
                                            <p class="mt-3 text-ui-muted italic">Без коментаря.</p>
                                        @endif
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-center gap-1" data-review-ticks>
                                @foreach($product->reviews as $review)
                                    <button type="button" data-review-tick="{{ $loop->index }}" class="p-2 text-ui-border hover:text-ui-accent" aria-label="Перейти до відгуку {{ $loop->iteration }}">
                                        <i class="fas fa-star text-xs"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="py-6 text-ui-muted">Поки немає відгуків. Будьте першим!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    // Ініціалізація модулів сторінки каталогу
    document.addEventListener('DOMContentLoaded', () => {
        window.initCatalogShowPage();
    });
    </script>
</x-app-layout>
