<?php

namespace App\Http\Controllers\Auth;

use App\Constants\HttpCodeConstant;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialLoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    protected SocialLoginService $socialLoginService;

    public function __construct() {
        $this->socialLoginService = new SocialLoginService();
    }

    /**
     * 구글 소셜 로그인 리다이렉트
     *
     * @return  RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
        // ->with(['access_type' => 'offline', 'prompt' => 'consent'])
        ->redirect();
    }

    /**
     * 구글 소셜 로그인 콜백 처리
     *
     * @return  [type]  [return description]
     */
    public function handleGoogleCallback()
    {
       return $this->socialLoginService->handleGoogleCallback();
    }

    /**
     * 네이버 소셜 로그인 리다이렉트
     *
     * @return  [type]  [return description]
     */
    public function redirectToNaver()
    {
        return Socialite::driver('naver')->redirect();
    }

    /**
     * 네이버 소셜 로그인 콜백 처리
     *
     * @return  [type]  [return description]
     */
    public function handleNaverCallback()
    {
        return $this->socialLoginService->handleNaverCallback();
    }

    /**
     * 통합회원 전환을 처리
     *
     * @return  JsonResponse
     */
    public function handleLinkUserAccount(): JsonResponse
    {
        if (empty(request()->post('userId')) || empty(request()->post('socialData'))) {
            response()->json(handleFailureResult(HttpCodeConstant::BAD_REQUEST, '소셜계정 정보가 존재하지 않습니다.'), HttpCodeConstant::BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }

        return $this->socialLoginService->handleLinkUserAccount(request()->post('userId'), request()->post('socialData'));
    }


    // ?
    public function linkGoogleCallback()
    {
        return $this->socialLoginService->handleNotSocialAccountsUser();
    }
}
