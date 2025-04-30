<?php

namespace App\Http\Controllers\Auth;

use App\Constants\HttpCodeConstant;
use App\Http\Controllers\Controller;
use App\Services\Social\Login\GoogleService;
use App\Services\Social\Login\NaverService;
use App\Services\SocialLoginService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    protected SocialLoginService $socialLoginService;
    protected GoogleService $googleService;
    protected NaverService $naverService;

    public function __construct()
    {
        $this->socialLoginService = new SocialLoginService();
        $this->googleService = new GoogleService();
        $this->naverService = new NaverService();
    }

    /**
     * 구글 소셜 로그인 리다이렉트
     *
     * @return  RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
        ->with(['access_type' => 'offline', 'prompt' => 'consent'])
        ->redirect();
    }

    /**
     * 구글 소셜 로그인 콜백 처리
     *
     * @return  mixed
     */
    public function handleGoogleCallback()
    {
        return $this->googleService->handleCallback();
    }

    /**
     * 구글 통합회원 전환 처리
     *
     * @return  JsonResponse
     */
    public function handleGoogleLinkUserAccount(): JsonResponse
    {
        if (empty(request()->post('userId')) || empty(request()->post('socialData'))) {
            response()->json(handleFailureResult(HttpCodeConstant::BAD_REQUEST, '소셜계정 정보가 존재하지 않습니다.'), HttpCodeConstant::BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }

        return $this->socialLoginService->linkUserAccount(request()->post('userId'), request()->post('socialData'));
    }

    /**
     * 네이버 소셜 로그인 리다이렉트
     *
     * @return  RedirectResponse
     */
    public function redirectToNaver()
    {
        return Socialite::driver('naver')->redirect();
    }

    /**
     * 네이버 소셜 로그인 콜백 처리
     *
     * @return  mixed
     */
    public function handleNaverCallback()
    {
        return $this->naverService->handleNaverCallback();
    }

    /**
     * 네이버 통합회원 전환 처리
     *
     * @return  JsonResponse
     */
    public function handleNaverLinkUserAccount(): JsonResponse
    {
        if (empty(request()->post('userId')) || empty(request()->post('socialData'))) {
            response()->json(handleFailureResult(HttpCodeConstant::BAD_REQUEST, '소셜계정 정보가 존재하지 않습니다.'), HttpCodeConstant::BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }

        return $this->socialLoginService->linkUserAccount(request()->post('userId'), request()->post('socialData'));
    }
}
