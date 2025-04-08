<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/social/link-account', function() {
    return view('user.link-account');
});

Route::name('google.')->group(function () {
    Route::get('login/google', [LoginController::class, 'redirectToGoogle']);
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('callback');


// TODO: login/google 이걸로 넘겨 파라미터 붙여서 그게 있으면 링크 처리

});
