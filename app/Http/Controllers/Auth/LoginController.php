<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialLoginService;
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
     * 구글 소셜 로그인 콜백 처리하는 메소드
     *
     * @return  [type]  [return description]
     */
    public function handleGoogleCallback()
    {
       return $this->socialLoginService->handleGoogleCallback();
    }

    public function handleLinkUserAccount()
    {
        return $this->socialLoginService->handleLinkUserAccount();
    }


    // ?
    public function linkGoogleCallback()
    {
        return $this->socialLoginService->handleNotSocialAccountsUser();
    }
}
