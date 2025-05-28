<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::get('users-roles', [SettingController::class, 'users_roles']);
        Route::post('users-roles', [SettingController::class, 'users_roles']);
        Route::get('permissions', [SettingController::class, 'permissions']);
        Route::post('permissions', [SettingController::class, 'permissions']);
        Route::get('profile', [SettingController::class, 'profile']);
        Route::post('profile', [SettingController::class, 'profile']);
        Route::delete('/destroy/{data}/{id}', [SettingController::class, 'destroy']);
    });
});
