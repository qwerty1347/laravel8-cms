<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::name('google.')->group(function () {
    Route::get('login/google', [LoginController::class, 'redirectToGoogle']);
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('callback');
});

//통합회원 전환 리다이렉트 페이지
Route::get('/social/link-account', function() {
    return view('user.link-account');
});

// 통합회원처리
Route::post('/social/link-account', [LoginController::class, 'handleLinkUserAccount'])->name('link-account');
