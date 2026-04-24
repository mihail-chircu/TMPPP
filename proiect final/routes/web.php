<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CatalogController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;

// Local-only screenshot helper: auto-login as a given user id so that
// the documentation capture script can reach admin/profile pages.
// Only available in local environment; never runs in production.
Route::get('/__dev/login/{user}', function (\App\Models\User $user) {
    abort_unless(app()->environment('local'), 404);
    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->intended('/');
})->name('__dev.login');

// Local-only: seed a sample cart + run one AddToCart command through the
// invoker so the Undo button has something to undo. Lets the docs capture
// the Command pattern in action.
Route::get('/__dev/seed-cart', function (
    \App\Services\CartService $cartService,
    \App\Services\Cart\CartCommandInvoker $invoker,
) {
    abort_unless(app()->environment('local'), 404);

    $userId = \Illuminate\Support\Facades\Auth::id();
    $sessionId = session()->getId();

    // Reset any previous demo cart.
    $resetCart = function (\App\Models\Cart $c): void {
        $c->items()->delete();
        $c->delete();
    };
    if ($userId) {
        \App\Models\Cart::where('user_id', $userId)->each($resetCart);
    }
    \App\Models\Cart::where('session_id', $sessionId)->each($resetCart);

    $cart = \App\Models\Cart::create([
        'user_id' => $userId,
        'session_id' => $userId ? null : $sessionId,
    ]);

    $products = \App\Models\Product::where('is_active', true)->inRandomOrder()->limit(2)->get();
    foreach ($products as $product) {
        \App\Models\CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'price' => $product->current_price,
            'quantity' => 1,
        ]);
    }

    // Run one tracked command so Undo becomes available.
    $third = \App\Models\Product::whereNotIn('id', $products->pluck('id'))->inRandomOrder()->first();
    if ($third) {
        $invoker->execute(new \App\Services\Cart\Commands\AddToCartCommand($third->id, 1));
    }

    return redirect()->route('cart.index');
})->name('__dev.seed_cart');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/filter', [CatalogController::class, 'filter'])->name('catalog.filter');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Cart Routes (work for both guests and auth users)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('remove');
    Route::post('/undo', [CartController::class, 'undo'])->name('undo');
});

// Auth Required Routes
Route::middleware('auth')->group(function () {
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
    Route::resource('categories', AdminCategoryController::class);
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/export/{format}', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('discounts', AdminDiscountController::class);
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
});
