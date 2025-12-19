<nav x-data="{ open: false }" class="bg-ui-bg border-b border-ui-border/40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                
                {{-- Для адміністратора --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-ui-muted bg-transparent hover:text-ui-fg hover:bg-ui-panel/40 focus:outline-none transition ease-in-out duration-150">
                                    <div>Адмін-панель</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                            <!-- Основне меню навігації -->
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.products.index')">
                                    <i class="fas fa-box mr-2"></i>{{ __('Товари') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.categories.index')">
                                    <i class="fas fa-tags mr-2"></i>{{ __('Категорії') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.orders.index')">
                                    <i class="fas fa-shopping-cart mr-2"></i>{{ __('Замовлення') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.users.index')">
                                    <i class="fas fa-users mr-2"></i>{{ __('Користувачі') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                <!-- Посилання навігації -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                        <i class="fas fa-store mr-1"></i>{{ __('Каталог') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Меню користувача / кошика -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                @auth
                <!-- Кошик -->
                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-ui-muted bg-transparent hover:text-ui-accent hover:bg-ui-panel/40 focus:outline-none transition ease-in-out duration-150">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    @php
                        $cartCount = auth()->user()->cartItems->sum('quantity');
                    @endphp
                    <span data-cart-count class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-ui-bg transform translate-x-1/2 -translate-y-1/2 bg-ui-accent2 rounded-full {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-ui-muted bg-transparent hover:text-ui-fg hover:bg-ui-panel/40 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('my-orders.index')">
                            <i class="fas fa-shopping-bag mr-2"></i>{{ __('Мої замовлення') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user mr-2"></i>{{ __('Профіль') }}
                        </x-dropdown-link>

                        <!-- Вихід з акаунта -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Вийти') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <!-- Кнопки для гостей -->
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-ui-border/40 text-sm font-medium rounded-md text-ui-fg bg-ui-panel hover:brightness-110 focus:outline-none transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Вхід
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-ui-bg bg-ui-accent hover:brightness-95 focus:outline-none transition">
                    <i class="fas fa-user-plus mr-2"></i>Реєстрація
                </a>
                @endauth
            </div>

            <!-- Кнопка мобільного меню -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-ui-muted hover:text-ui-fg hover:bg-ui-panel/40 focus:outline-none focus:bg-ui-panel/40 focus:text-ui-fg transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Мобільне меню навігації -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                <i class="fas fa-store mr-2"></i>{{ __('Каталог') }}
            </x-responsive-nav-link>
        </div>

        <!-- Опції для мобільного меню -->
        <div class="pt-4 pb-1 border-t border-ui-border/40">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-ui-fg">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-ui-muted">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('my-orders.index')">
                    <i class="fas fa-shopping-bag mr-2"></i>{{ __('Мої замовлення') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user mr-2"></i>{{ __('Профіль') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('cart.index')">
                    <i class="fas fa-shopping-cart mr-2"></i>{{ __('Кошик') }}
                </x-responsive-nav-link>

                <!-- Вихід з акаунта -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Вийти') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="px-4 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-ui-accent text-ui-bg rounded-md hover:brightness-95 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Увійти') }}
                </a>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-ui-border/40 rounded-md hover:bg-ui-panel/40 transition">
                    <i class="fas fa-user-plus mr-2"></i>{{ __('Реєстрація') }}
                </a>
            </div>
            @endauth
        </div>
        {{-- Для адміністратора --}}
        @auth
            @if(auth()->user()->role === 'admin')
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-ui-muted bg-transparent hover:text-ui-fg hover:bg-ui-panel/40 focus:outline-none transition ease-in-out duration-150">
                            <div>Адмін-панель</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('admin.products.index')">
                            <i class="fas fa-box mr-2"></i> Товари
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('admin.categories.index')">
                            <i class="fas fa-tags mr-2"></i> Категорії
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('admin.orders.index')">
                            <i class="fas fa-shopping-cart mr-2"></i> Замовлення
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('admin.users.index')">
                            <i class="fas fa-users mr-2"></i> Користувачі
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            @endif
        @endauth
    </div>
</nav>
