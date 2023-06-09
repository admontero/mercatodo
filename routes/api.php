<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** Autenticación */
Route::post('/register', [App\Api\Auth\Controllers\AuthController::class, 'register'])
    ->name('api.register');
Route::post('/login', [App\Api\Auth\Controllers\AuthController::class, 'login'])
    ->name('api.login');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [App\Api\Auth\Controllers\AuthController::class, 'logout'])
        ->name('api.logout');
});
/** */

Route::get('/countries', App\Api\Country\Controllers\CountryController::class)
    ->name('api.countries');
Route::get('/countries/{country}/states', App\Api\State\Controllers\StateController::class)
    ->name('api.countries.states');
Route::get('/states/{state}/cities', App\Api\City\Controllers\CityController::class)
    ->name('api.states.cities');

/** Rutas públicas de productos */
Route::group(['middleware' => ['cache_product']], function () {
    Route::get('/products', [App\Api\Product\Controllers\ProductController::class, 'index'])
        ->name('api.products.index');
    Route::get('/products/price-range', App\Api\Product\Controllers\ProductPriceRangeController::class)
        ->name('api.products.range-price');
    Route::get('/products/categories', App\Api\Category\Controllers\ProductCategoryController::class)
        ->name('api.products.categories');
});
/** */

/** Rutas protegidas del Administrador */
Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    //Customer routes
    Route::get('/admin/customers', [App\ApiAdmin\User\Controllers\CustomerController::class, 'index'])
        ->name('api.admin.customers.index');
    Route::get('/admin/customers/{user}', [App\ApiAdmin\User\Controllers\CustomerController::class, 'show'])
        ->name('api.admin.customers.show');
    Route::put('/admin/customers/{user}', [App\ApiAdmin\User\Controllers\CustomerController::class, 'update'])
        ->name('api.admin.customers.update');
    Route::post('/admin/customers/{user}/activate', App\ApiAdmin\User\Controllers\CustomerActivationController::class)
        ->name('api.admin.customers.activate');
    Route::post('/admin/customers/{user}/inactivate', App\ApiAdmin\User\Controllers\CustomerInactivationController::class)
        ->name('api.admin.customers.inactivate');

    //Categories routes
    Route::get('/admin/categories', [App\ApiAdmin\Category\Controllers\CategoryController::class, 'index'])
        ->name('api.admin.categories.index');
    Route::post('/admin/categories', [App\ApiAdmin\Category\Controllers\CategoryController::class, 'store'])
        ->name('api.admin.categories.store');
    Route::get('/admin/categories/{category:slug}', [App\ApiAdmin\Category\Controllers\CategoryController::class, 'show'])
        ->name('api.admin.categories.show');
    Route::put('/admin/categories/{category:slug}', [App\ApiAdmin\Category\Controllers\CategoryController::class, 'update'])
        ->name('api.admin.categories.update');

    //Product routes
    Route::get('/admin/products', [App\ApiAdmin\Product\Controllers\ProductController::class, 'index'])
        ->name('api.admin.products.index');
    Route::post('/admin/products', [App\ApiAdmin\Product\Controllers\ProductController::class, 'store'])
        ->name('api.admin.products.store');
    Route::get('/admin/products/{product:slug}', [App\ApiAdmin\Product\Controllers\ProductController::class, 'show'])
        ->name('api.admin.products.show');
    Route::put('/admin/products/{product:slug}', [App\ApiAdmin\Product\Controllers\ProductController::class, 'update'])
        ->name('api.admin.products.update');
    Route::post('/admin/products/{product:slug}/activate', App\ApiAdmin\Product\Controllers\ProductActivationController::class)
        ->name('api.admin.products.activate');
    Route::post('/admin/products/{product:slug}/inactivate', App\ApiAdmin\Product\Controllers\ProductInactivationController::class)
        ->name('api.admin.products.inactivate');
});
/** */

/** Rutas protegidas del Cliente */
Route::group(['middleware' => ['auth:api', 'role:customer', 'verified']], function () {
    Route::get('/customer/customers/{user}', [App\ApiCustomer\User\Controllers\CustomerController::class, 'show'])
        ->name('api.customer.customers.show');
    Route::put('/customer/customers/{user}/update-profile', App\ApiCustomer\User\Controllers\UpdateProfileController::class)
        ->name('api.customer.customers.update-profile');

    //Payment routes
    Route::post('/customer/payments', [App\ApiCustomer\Payment\Controllers\PaymentController::class, 'processPayment'])
        ->name('api.customer.payments.processPayment');
    Route::put('/customer/payments/{order:code}', [App\ApiCustomer\Payment\Controllers\PaymentController::class, 'retryPayment'])
        ->name('api.customer.payments.retryPayment');

    //Order routes
    Route::get('/customer/orders', [App\ApiCustomer\Order\Controllers\OrderController::class, 'index'])
        ->name('api.customer.orders.index');
});
/** */
