<?php

namespace App\Services\Social\Login;

use App\Constants\SocialConstant;
use App\Services\SocialLoginService;

class KakaoService extends SocialLoginService
{
    public function __construct()
    {
        parent::__construct();
        $this->socialProvider = SocialConstant::KAKAO;
    }

    /**
     * 카카오 소셜 로그인 콜백 처리하는 메소드
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
    public function handleKakaoCallback()
    {

    }
}
