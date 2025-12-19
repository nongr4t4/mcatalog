<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Адмін-панель</title>
    <!-- Асети (Vite): CSS/JS для адмін-UI -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Іконки (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-ui-bg text-ui-fg">
    <!-- Навігація адмін-панелі -->
    <nav class="bg-ui-panel text-ui-fg border-b border-ui-border/40 sticky top-0 z-50 shadow-lg shadow-black/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Лого + навігація -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold flex items-center space-x-2 text-ui-accent hover:brightness-95 transition">
                        <i class="fas fa-shield-alt"></i>
                        <span>Адмін-панель</span>
                    </a>
                    
                    <div class="hidden md:flex items-center space-x-1 ml-6">
                        <!-- Товари (дропдаун) -->
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-ui-fg transition {{ request()->routeIs('admin.products.*') ? 'bg-ui-bg/40' : 'hover:bg-ui-bg/30' }}">
                                    <i class="fas fa-boxes mr-2"></i>
                                    <span>Товари</span>
                                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.products.index')">
                                    <i class="fas fa-list mr-2"></i>Усі товари
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.products.create')">
                                    <i class="fas fa-plus-circle mr-2"></i>Створити товар
                                </x-dropdown-link>
                                <div class="border-t border-ui-border/20"></div>
                                <x-dropdown-link :href="route('admin.products.index', ['sort' => 'newest'])">
                                    <i class="fas fa-clock mr-2"></i>Нові товари
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.products.index', ['price_from' => 1000])">
                                    <i class="fas fa-chart-line mr-2"></i>Дорогі товари
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>

                        <!-- Категорії (дропдаун) -->
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-ui-fg transition {{ request()->routeIs('admin.categories.*') ? 'bg-ui-bg/40' : 'hover:bg-ui-bg/30' }}">
                                    <i class="fas fa-tags mr-2"></i>
                                    <span>Категорії</span>
                                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.categories.index')">
                                    <i class="fas fa-list mr-2"></i>Усі категорії
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.categories.create')">
                                    <i class="fas fa-plus-circle mr-2"></i>Створити категорію
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>

                        <!-- Замовлення (дропдаун) -->
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-ui-fg transition {{ request()->routeIs('admin.orders.*') ? 'bg-ui-bg/40' : 'hover:bg-ui-bg/30' }}">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    <span>Замовлення</span>
                                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.orders.index')">
                                    <i class="fas fa-list mr-2"></i>Усі замовлення
                                </x-dropdown-link>
                                <div class="border-t border-ui-border/20"></div>
                                <x-dropdown-link :href="route('admin.orders.index', ['status' => 'pending'])">
                                    <i class="fas fa-hourglass-half mr-2 text-yellow-500"></i>Очікують
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.orders.index', ['status' => 'completed'])">
                                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Виконані
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.orders.index', ['status' => 'cancelled'])">
                                    <i class="fas fa-times-circle mr-2 text-red-500"></i>Скасовані
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>

                        <!-- Користувачі -->
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium text-ui-fg transition {{ request()->routeIs('admin.users.*') ? 'bg-ui-bg/40' : 'hover:bg-ui-bg/30' }}">
                            <i class="fas fa-users mr-2"></i>Користувачі
                        </a>

                        <!-- Повернутись до каталогу -->
                        <a href="{{ route('catalog.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium text-ui-muted hover:text-ui-accent transition hover:bg-ui-bg/30"
                           target="_blank">
                            <i class="fas fa-store mr-2"></i>До каталогу
                        </a>
                    </div>
                </div>

                <!-- Профіль адміна -->
                <div class="flex items-center space-x-4">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-ui-fg hover:bg-ui-bg/30 transition">
                                <div class="w-8 h-8 bg-ui-accent rounded-full flex items-center justify-center font-bold text-ui-bg mr-2">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-ui-border/20">
                                <p class="text-sm font-medium text-ui-fg">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-ui-muted">{{ auth()->user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('admin.dashboard')">
                                <i class="fas fa-tachometer-alt mr-2"></i>Дашборд
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="fas fa-user-cog mr-2"></i>Профіль
                            </x-dropdown-link>
                            <div class="border-t border-ui-border/20"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-ui-accent2 hover:bg-ui-bg/40 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Вихід
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Флеш-повідомлення про успіх/помилку -->
        @if ($errors->any())
        <div class="mb-6 bg-ui-bg border border-ui-accent2 rounded-lg p-4">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-ui-accent2 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-ui-accent2 font-semibold mb-2">Виникли помилки:</h3>
                    <ul class="list-disc list-inside text-ui-fg text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if (session('success'))
        <div class="mb-6 bg-ui-bg border border-ui-border/40 rounded-lg p-4 flex items-center animate-fade-out-5s">
            <i class="fas fa-check-circle text-ui-accent mr-3"></i>
            <span class="text-ui-fg font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6 bg-ui-bg border border-ui-accent2 rounded-lg p-4 flex items-center">
            <i class="fas fa-times-circle text-ui-accent2 mr-3"></i>
            <span class="text-ui-fg font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Основний контент сторінки -->
        @yield('content')
    </div>

    <style>
        @keyframes fadeOutAfter5s {
            0% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }
        .animate-fade-out-5s {
            animation: fadeOutAfter5s 5s ease-in-out forwards;
        }
    </style>
</body>
</html>