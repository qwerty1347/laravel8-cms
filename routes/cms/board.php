<?php

use App\Http\Controllers\ContentsManagement\BoardConfigController;

Route::prefix('config')->name('config.')->group(function () {
    Route::get('index', [BoardConfigController::class, 'index'])->name('index');
    Route::post('store', [BoardConfigController::class, 'store'])->name('store');
});
