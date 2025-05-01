<?php

namespace App\Services\Social\Login;

use Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\SocialConstant;
use App\Services\SocialLoginService;

class NaverService extends SocialLoginService
{
    public function __construct() {
        parent::__construct();
        $this->socialProvider = SocialConstant::NAVER;
    }

    /**
     * 네이버 소셜 로그인 콜백 처리하는 메소드
     *
     * 1. User 있고 SocialAccount 없는 경우
     *  - 블레이드 페이지로 이동 (통합 하시겠습니까?)
     *  - Y: SocialAccount 생성, OauthToken 생성
     *  - N: 가입된 회원아이디로 로그인해 주세요.
     *
     * 2. User 없는 경우
     *  - 생성 User 생성, SocialAccount 생성, OauthToken 생성
     *  - 로그인
     *
     * 3. User 있고 SocialAccount 있는 경우
     *  - 로그인
     *
     * @return  mixed  (view|redirect)
     */
    public function handleNaverCallback()
    {
        try {
            DB::beginTransaction();

            $socialUser = Socialite::driver(SocialConstant::NAVER)->stateless()->user();
            $user = $this->userRepository->getUserWithSocialAccountRow($socialUser->getEmail(), $socialUser->getId());

            if (isset($user) && $user->socialAccounts->isEmpty()) {
                return parent::handleLinkUserAccount($socialUser, $user);
            }

            if (!isset($user)) {
                $user = parent::handleNotUser($socialUser);
            }
            else {
                parent::handleSocialAccountsUser($user);
            }

            Auth::login($user, true);
            DB::commit();

            return redirect()->to('/admin');
        }
        catch (Exception $e) {
            DB::rollBack();
            $logMessage = "#1 ".$e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
            logMessage('adminlog', 'error', $logMessage);

            dd($e, $e->getMessage(), $e->getFile(), $e->getLine());

            return redirect('login');
        }
    }
}
