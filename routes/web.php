<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/** Autenticación */
Route::get('login', [App\Web\Auth\Controllers\LoginController::class, 'showLoginForm'])
    ->name('login');
Route::post('login', [App\Web\Auth\Controllers\LoginController::class, 'login']);
Route::post('logout', [App\Web\Auth\Controllers\LoginController::class, 'logout'])
    ->name('logout');
Route::get('register', [App\Web\Auth\Controllers\RegisterController::class, 'showRegistrationForm'])
    ->name('register');
Route::post('register', [App\Web\Auth\Controllers\RegisterController::class, 'register']);
Route::get('verify-email', [App\Web\Auth\Controllers\VerificationController::class, 'show'])
    ->name('verification.notice');
Route::get('verify-email/{id}/{hash}', [App\Web\Auth\Controllers\VerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('email/verification-notification', [App\Web\Auth\Controllers\VerificationController::class, 'resend'])
    ->name('verification.resend');
/** */

/** Rutas públicas de productos */
Route::get('/', [App\Web\Product\Controllers\ProductController::class, 'index'])
    ->name('products.index');
/** */

/** Rutas protegidas del Cliente */
Route::group(['middleware' => ['auth', 'verified', 'role:customer']], function () {
    Route::get('/profile', [App\Web\Profile\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
});
/** */

/** Rutas protegidas del Administrador */
Route::group(['middleware' => ['auth', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [App\WebAdmin\Dashboard\Controllers\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    //Customers
    Route::get('/customers', [App\WebAdmin\User\Controllers\CustomerController::class, 'index'])
        ->name('admin.customers.index');

    Route::get('/customers/{customer}/edit', [App\WebAdmin\User\Controllers\CustomerController::class, 'edit'])
        ->name('admin.customers.edit');

    //Categories
    Route::get('/categories', [App\WebAdmin\Category\Controllers\CategoryController::class, 'index'])
        ->name('admin.categories.index');

    Route::get('/categories/create', [App\WebAdmin\Category\Controllers\CategoryController::class, 'create'])
        ->name('admin.categories.create');

    Route::get('/categories/{category:slug}/edit', [App\WebAdmin\Category\Controllers\CategoryController::class, 'edit'])
        ->name('admin.categories.edit');

    //Products
    Route::get('/products', [App\WebAdmin\Product\Controllers\ProductController::class, 'index'])
        ->name('admin.products.index');
    Route::get('/products/create', [App\WebAdmin\Product\Controllers\ProductController::class, 'create'])
        ->name('admin.products.create');
    Route::get('/products/{product:slug}/edit', [App\WebAdmin\Product\Controllers\ProductController::class, 'edit'])
        ->name('admin.products.edit');
});
/** */
