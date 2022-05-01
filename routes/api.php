<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Public Routes, Accessable without User authentication / without User Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/product', [ProductController::class, 'index'])->name('product.show');
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/users', [AuthController::class, 'index']);

// Protected Routes, Not accessable without the valid User Login(Having Token)
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.delete');
    Route::post('/logout', [AuthController::class, 'logout']);
});