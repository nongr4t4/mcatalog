<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\CatalogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactsController;
/*
|--------------------------------------------------------------------------
| Web Routes (UI)
|--------------------------------------------------------------------------
| Секції:
| - Публічні сторінки каталогу
| - Маршрути для авторизованих користувачів (профіль/кошик/замовлення)
| - Адмін-панель
*/

// Головна: редирект у каталог
Route::get('/contacts', [ContactsController::class, 'show']);
Route::get('/', function () {
    return redirect()->route('catalog.index');
});

// Каталог (публічно)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');

// Авторизовані користувачі: профіль/кошик/відгуки/замовлення
Route::middleware(['auth'])->group(function () {
    
    // Профіль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Кошик
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update'); // Додано для зміни кількості
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Відгуки (для товарів)
    Route::post('/catalog/{product}/reviews', [ReviewController::class, 'store'])->name('catalog.reviews.store');
    Route::patch('/catalog/{product}/reviews/{review}', [ReviewController::class, 'update'])->name('catalog.reviews.update');
    Route::delete('/catalog/{product}/reviews/{review}', [ReviewController::class, 'destroy'])->name('catalog.reviews.destroy');

    // Оформлення замовлення
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Мої замовлення
    Route::get('/my-orders', [ClientOrderController::class, 'index'])->name('my-orders.index');
    Route::get('/my-orders/{order}', [ClientOrderController::class, 'show'])->name('my-orders.show');

    // Дашборд (редирект)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('catalog.index');
    })->name('dashboard');
});

// Адмін-панель: керування товарами/категоріями/замовленнями/користувачами
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
