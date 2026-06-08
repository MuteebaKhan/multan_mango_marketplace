<?php
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;

// Naya secure dynamic routing rule
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// 1. Default Route 
Route::get('/', function () {
    return redirect()->route('products.index');
});

// 2. Public Products Index (Bina login ke sab dekh sakein)
Route::get('products', [ProductController::class, 'index'])->name('products.index');

// Admin Secure Routes Group (Bina Kernel registration ke)
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function() {
    // Main Dashboard Layout (Dusri Picture jaisa)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // JazzCash TID Verification Action
    Route::post('/orders/{order}/verify-payment', [AdminController::class, 'verifyPayment'])->name('orders.verify-payment');

    Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons.index');
    Route::post('/coupons/store', [AdminController::class, 'storeCoupon'])->name('coupons.store');
    Route::delete('/coupons/{coupon}', [AdminController::class, 'deleteCoupon'])->name('coupons.destroy');
    
});

// 3. Protected Products CRUD Routes (Hamesha dynamic show route se UPAR hona chahiye)
Route::middleware(['auth', 'role:vendor'])->group(function () {

    Route::get('/vendor/dashboard', [DashboardController::class, 'index'])->name('vendor.dashboard');

    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::post('/vendor/order/{id}/update-status', [DashboardController::class, 'updateOrderStatus'])->name('vendor.order.updateStatus');
});

// 4. Dynamic Public Show Route (Hamesha aakhir mein aayega taake 'create' ko intercept na kare!)
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// 5. User Profile Management (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Checkout form dikhane ke liye - Fixed Controller Binding
    Route::get('/checkout/{product?}', [OrderController::class, 'checkout'])->name('checkout');
    
    Route::post('/api/apply-coupon', [OrderController::class, 'applyCoupon'])->name('api.apply.coupon');
    // Order submit karne ke liye
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Order ka status update karne ke liye (Vendor ke liye)
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});


Route::middleware(['auth'])->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

// Breeze Auth System (Login/Register files)
require __DIR__.'/auth.php';