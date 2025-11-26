<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\CetakController;

Route::prefix('unduhan')->name('unduhan.')->group( function(){
    Route::get('/template-pd', [ExportController::class, 'template_pd'])->name('template-pd');
});
Route::prefix('cetak')->name('cetak.')->group( function(){
    Route::get('/{id}', [CetakController::class, 'index'])->name('document');
});
Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
