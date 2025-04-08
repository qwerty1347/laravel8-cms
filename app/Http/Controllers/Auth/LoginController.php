<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialLoginService;

class LoginController extends Controller
{
    protected SocialLoginService $socialLoginService;

    public function __construct() {
        $this->socialLoginService = new SocialLoginService();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        // ->with(['access_type' => 'offline', 'prompt' => 'consent'])
        ->redirect();
    }

    public function handleGoogleCallback()
    {
       return $this->socialLoginService->handleGoogleCallback();
    }

    public function linkGoogleCallback()
    {
        return $this->socialLoginService->handleNotSocialAccountsUser();
    }
}
