<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// 구글 소셜 로그인
Route::name('google.')->group(function () {
    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login');
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('callback');
    Route::post('login/google/link-account', [LoginController::class, 'handleGoogleLinkUserAccount'])->name('link-account');
});

// 네이버 소셜 로그인
Route::name('naver.')->group(function () {
    Route::get('login/naver', [LoginController::class, 'redirectToNaver'])->name('login');
    Route::get('login/naver/callback', [LoginController::class, 'handleNaverCallback'])->name('callback');
    Route::post('login/naver/link-account', [LoginController::class, 'handleNaverLinkUserAccount'])->name('link-account');
});

// 카카오 소셜 로그인
Route::name('kakao.')->group(function () {
    Route::get('login/kakao', [LoginController::class, 'redirectToKakao'])->name('login');
    Route::get('login/kakao/callback', [LoginController::class, 'handleKakaoCallback'])->name('callback');
    Route::post('login/kakao/link-account', [LoginController::class, 'handleKakaoLinkUserAccount'])->name('link-account');
});
