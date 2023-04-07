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

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::put('/customers/{customer}', [App\Http\Controllers\Api\CustomerController::class, 'update']);
});

Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    Route::get('/customers', [App\Http\Controllers\Api\CustomerController::class, 'index']);
    Route::post('/customers/{user}/status', App\Http\Controllers\Api\CustomerStatusController::class);

    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::put('/categories/{category:slug}', [App\Http\Controllers\Api\CategoryController::class, 'update']);
});
