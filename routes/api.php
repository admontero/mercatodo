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

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register'])
    ->name('api.register');
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login'])
    ->name('api.login');

Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index'])
    ->name('api.products.index');
Route::get('/products/price-range', App\Http\Controllers\Api\ProductPriceRangeController::class)
    ->name('api.products.range-price');

Route::get('/products/categories', App\Http\Controllers\Api\ProductCategoryController::class)
    ->name('api.products.categories');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])
        ->name('api.logout');
    Route::put('/customers/{customer}', [App\Http\Controllers\Api\CustomerController::class, 'update'])
        ->name('api.customers.update');
});

Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    //Customer routes
    Route::get('/admin/customers', [App\Http\Controllers\Api\CustomerController::class, 'index'])
        ->name('api.admin.customers.index');
    Route::post('/admin/customers/{customer}/status', App\Http\Controllers\Api\CustomerStatusController::class)
        ->name('api.admin.customers.update-status');

    //Categories routes
    Route::get('/admin/categories', [App\Http\Controllers\Api\CategoryController::class, 'index'])
        ->name('api.admin.categories.index');
    Route::post('/admin/categories', [App\Http\Controllers\Api\CategoryController::class, 'store'])
        ->name('api.admin.categories.store');
    Route::get('/admin/categories/{category:slug}', [App\Http\Controllers\Api\CategoryController::class, 'show'])
        ->name('api.admin.categories.show');
    Route::put('/admin/categories/{category:slug}', [App\Http\Controllers\Api\CategoryController::class, 'update'])
        ->name('api.admin.categories.update');

    //Product routes
    Route::get('/admin/products', [App\Http\Controllers\Api\Admin\ProductController::class, 'index'])
        ->name('api.admin.products.index');
    Route::post('/admin/products', [App\Http\Controllers\Api\Admin\ProductController::class, 'store'])
        ->name('api.admin.products.store');
    Route::get('/admin/products/{product:slug}', [App\Http\Controllers\Api\Admin\ProductController::class, 'show'])
        ->name('api.products.show');
    Route::put('/admin/products/{product:slug}', [App\Http\Controllers\Api\Admin\ProductController::class, 'update'])
        ->name('api.admin.products.update');
    Route::post('/admin/products/{product:slug}/status', App\Http\Controllers\Api\Admin\ProductStatusController::class)
        ->name('api.admin.products.update-status');
});
