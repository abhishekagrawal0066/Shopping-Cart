<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;



// Home Route
// Route::get('/', function () {
//     return view('home');
// })->name('home');

// Admin Routes
// Route::middleware(['admin'])->group(function(){
//     Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

//     
//         Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//     });
// });

Route::middleware('admin')->group(function(){
    // Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Route::resource('/admin/categories', CategoryController::class);
    // Route::get('/admin/getUsers', [UserController::class, 'getUsers'])->name('users.getUsers');
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // Route::get('/orders-data', [OrderController::class, 'getOrdersData'])->name('orders.data');
    // Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    // Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    // Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    // Route::get('/admin/users/data', [UserController::class, 'getUsers'])->name('users.data');
    // Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    // Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    // Route::resource('/admin/products', ProductController::class);
    // Route::delete('/admin/products/{product}/soft-delete', [ProductController::class, 'softDelete'])->name('products.destroySoft');
    // // Hard delete route
    // Route::delete('/admin/products/{product}/hard-delete', [ProductController::class, 'hardDelete'])->name('products.destroyHard');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/categories', CategoryController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders-data', [OrderController::class, 'getOrdersData'])->name('orders.data');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/data', [UserController::class, 'getUsers'])->name('users.data');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/admin/products/{product}/soft-delete', [ProductController::class, 'destroySoft'])->name('products.destroySoft');
    Route::delete('/admin/products/{product}/hard-delete', [ProductController::class, 'destroyHard'])->name('products.destroyHard');
    Route::post('/admin/products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    // Route::post('/admin/products/upload-images', [ProductController::class, 'uploadImages'])->name('products.uploadImages');
    Route::post('/products/uploadImages', [ProductController::class, 'uploadImages'])->name('products.uploadImages');

    Route::resource('/admin/products', ProductController::class);
});


// User Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::any('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('cart/checkout', [CartController::class, 'showCheckoutPage'])->name('cart.checkout')->middleware('auth');
Route::post('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout.submit');
Route::post('order/place', [OrderController::class, 'placeOrder'])->name('order.place')->middleware('auth');

