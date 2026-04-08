<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {




    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Route::middleware('auth:sanctum')->group(function () {
        //     Route::get('/me', [AuthController::class, 'user']);
        //     Route::post('/logout', [AuthController::class, 'logout']);
        // });
    });

    Route::prefix('user')->group(function () {
      

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'user']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    });
});
