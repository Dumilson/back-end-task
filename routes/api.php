<?php

use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\User\Manager\UserController;
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

Route::prefix('v1')->group(function () {
    Route::post('auth', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::prefix('users')->controller(UserController::class)->group(function () {
            Route::post('store', 'store');
            Route::get('get_all_users_paginate', 'getAllUsersPaginate');
            Route::get('get_all_users', 'getAllUsers');
            Route::get('get_tasks_user/{id?}', 'getAllTaskUser');
        });

        Route::prefix('tasks')->controller(TaskController::class)->group(function () {
            Route::post('store', 'store');
            Route::get('/', 'index');
            Route::delete('delete/{task?}', 'destroy');
            Route::put('update/{task?}', 'update');
        });
    });
});
