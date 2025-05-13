<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.index');
    });
});

Route::name('social.')->group(base_path('routes/social.php'));

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('board')->name('board.')->group(base_path('/routes/cms/board.php'));
});
