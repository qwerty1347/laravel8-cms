<?php

use App\Http\Controllers\ContentsManagement\BoardConfigController;
use App\Http\Controllers\ContentsManagement\BoardPostController;

Route::prefix('config')->name('config.')->group(function () {
    Route::get('index', [BoardConfigController::class, 'index'])->name('index');
    Route::post('store', [BoardConfigController::class, 'store'])->name('store');
});

Route::prefix('post')->name('post.')->group(function () {
    Route::get('index', [BoardPostController::class, 'index'])->name('index');
    Route::post('store', [BoardPostController::class, 'store'])->name('store');
});
