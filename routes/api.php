<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\ProductCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api;" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::patch('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    Route::get('/categories', [ProductCategoryController::class, 'index']);
    Route::get('/categories/{id}', [ProductCategoryController::class, 'show']);
    Route::post('/categories', [ProductCategoryController::class, 'store']);
    Route::put('/categories/{id}', [ProductCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [ProductCategoryController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
