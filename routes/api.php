<?php

declare(strict_types=1);

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client;
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
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::prefix('client')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', [Client\CategoryController::class, 'index']);
            Route::get('{category:slug}', [Client\CategoryController::class, 'show']);
        });

        Route::prefix('tags')->group(function () {
            Route::get('/', [Client\TagController::class, 'index']);
            Route::get('{tag}', [Client\TagController::class, 'show']);
        });

        Route::prefix('posts')->group(function () {
            Route::get('/', [Client\PostController::class, 'index']);
            Route::get('{slug}', [Client\PostController::class, 'show']);
        });
    });

    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', [Admin\CategoryController::class, 'index']);
            Route::post('/', [Admin\CategoryController::class, 'store']);
            Route::get('{category}', [Admin\CategoryController::class, 'show']);
            Route::put('{category}', [Admin\CategoryController::class, 'update']);
            Route::delete('{category}', [Admin\CategoryController::class, 'destroy']);
        });

        Route::prefix('tags')->group(function () {
            Route::get('/', [Admin\TagController::class, 'index']);
            Route::post('/', [Admin\TagController::class, 'store']);
            Route::get('{tag}', [Admin\TagController::class, 'show']);
            Route::put('{tag}', [Admin\TagController::class, 'update']);
            Route::delete('{tag}', [Admin\TagController::class, 'destroy']);
        });

        Route::prefix('posts')->group(function () {
            Route::get('/', [Admin\Post\PostController::class, 'index']);
            Route::get('status', [Admin\Post\PostStatusController::class, 'index']);
            Route::post('/', [Admin\Post\PostController::class, 'store']);
            Route::get('{post}', [Admin\Post\PostController::class, 'show']);
            Route::put('{post}', [Admin\Post\PostController::class, 'update']);
            Route::delete('{post}', [Admin\Post\PostController::class, 'destroy']);
        });
    });
});
