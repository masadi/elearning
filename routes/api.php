<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReferensiController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\LamanController;
use App\Http\Controllers\KontenController;

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
        Route::get('generate-user', [SettingController::class, 'generate_user']);
    });
    Route::group(['prefix' => 'referensi'], function () {
        Route::get('/', [ReferensiController::class, 'index']);
        Route::post('store', [ReferensiController::class, 'store']);
        Route::post('import', [ReferensiController::class, 'import']);
        Route::delete('/destroy/{data}/{id}', [ReferensiController::class, 'destroy']);
        Route::get('/show', [ReferensiController::class, 'show']);
    });
    Route::group(['prefix' => 'table'], function () {
        Route::get('/', [TableController::class, 'index']);
    });
    Route::group(['prefix' => 'pelatihan'], function () {
        Route::get('/', [PelatihanController::class, 'index']);
        Route::post('absen', [PelatihanController::class, 'absen']);
        Route::post('unggah', [PelatihanController::class, 'unggah']);
        Route::post('get-soal', [PelatihanController::class, 'get_soal']);
        Route::post('tes-selesai', [PelatihanController::class, 'tes_selesai']);
    });
    Route::group(['prefix' => 'laman'], function () {
        Route::get('/', [LamanController::class, 'index']);
        Route::get('/galeri/{slug}', [LamanController::class, 'get_galeri']);
        Route::get('/program/{slug}', [LamanController::class, 'get_program']);
        Route::post('/store', [LamanController::class, 'store']);
        Route::delete('/destroy/{id}', [LamanController::class, 'destroy']);
        Route::delete('/galeri/destroy/{id}', [LamanController::class, 'destroy_galeri']);
        Route::delete('/program/destroy/{id}', [LamanController::class, 'destroy_program']);
    });
    Route::group(['prefix' => 'konten'], function () {
        Route::post('/store', [KontenController::class, 'store']);
        Route::delete('/destroy/{data}/{id}', [KontenController::class, 'destroy']);
    });
});
