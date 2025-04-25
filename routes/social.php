<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// 구글 소셜 로그인
Route::name('google.')->group(function () {
    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login');
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('callback');
});

// 네이버 소셜 로그인
Route::name('naver.')->group(function () {
    Route::get('login/naver', [LoginController::class, 'redirectToNaver'])->name('login');
    Route::get('login/naver/callback', [LoginController::class, 'handleNaverCallback'])->name('callback');
});


//통합회원 전환 리다이렉트 페이지
Route::get('/social/link-account', function() {
    return view('user.link-account');
});

// 통합회원처리
Route::post('/social/link-account', [LoginController::class, 'handleLinkUserAccount'])->name('link-account');
