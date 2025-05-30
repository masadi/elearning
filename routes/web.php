<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::prefix('unduhan')->name('unduhan.')->group( function(){
    Route::get('/template-ptk', [ExportController::class, 'template_ptk'])->name('template-ptk');
});
Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
