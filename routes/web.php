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

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
});

Route::group(['middleware' => ['auth', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    //Customers
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])
        ->name('admin.customers.index');

    Route::get('/customers/{customer}/edit', [App\Http\Controllers\Admin\CustomerController::class, 'edit'])
        ->name('admin.customers.edit');

    //Categories
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])
        ->name('admin.categories.index');

    Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])
        ->name('admin.categories.create');

    Route::get('/categories/{category:slug}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])
        ->name('admin.categories.edit');
});
