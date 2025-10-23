<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::prefix('unduhan')->name('unduhan.')->group( function(){
    Route::get('/template-pd', [ExportController::class, 'template_pd'])->name('template-pd');
});
Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
