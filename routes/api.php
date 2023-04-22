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
    Route::get('/customers', [App\Http\Controllers\Api\CustomerController::class, 'index'])
        ->name('api.customers.index');
    Route::post('/customers/{customer}/status', App\Http\Controllers\Api\CustomerStatusController::class)
        ->name('api.customers.update-status');

    //Categories routes
    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index'])
        ->name('api.categories.index');
    Route::post('/categories', [App\Http\Controllers\Api\CategoryController::class, 'store'])
        ->name('api.categories.store');
    Route::get('/categories/{category:slug}', [App\Http\Controllers\Api\CategoryController::class, 'show'])
        ->name('api.categories.show');
    Route::put('/categories/{category:slug}', [App\Http\Controllers\Api\CategoryController::class, 'update'])
        ->name('api.categories.update');
});
