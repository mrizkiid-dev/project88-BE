<?php

// use App\Http\Controllers\Api\v1\OrderController;
// use App\Http\Controllers\Api\v1\TestController;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\TestController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\AuthAdminController;
use App\Http\Middleware\AuthAdminMiddleware;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//TODO test will delete soon
Route::get('/test', [TestController::class, 'get']);

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

Route::prefix('admin')->middleware(['auth:sanctum', AuthAdminMiddleware::class])->group(function() {
    Route::controller(AuthAdminController::class)->group(function () {
        Route::post('/register', 'register');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'get');
        Route::get('/orders/{id}', 'getWithId');
        Route::patch('/orders/{id}/status', 'updateStatusOrder');
    });

    Route::controller(ProductController::class)->group(function () {
        //TODO test will delete soon
        Route::get('/product/test', 'test');

        Route::get('/product', 'index');
        Route::post('/product', 'storeProduct');

        Route::get('/product/{id}', 'getWithId');
        Route::patch('/product/{id}', 'patchWithId');
        Route::delete('/product/{id}', 'delete');
    });
});
