<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Two\User as TwoUser;
use App\Repositories\UserRepository;
use App\Repositories\SocialAccountRepository;
use App\Repositories\Auth\OauthTokenRepository;
use Illuminate\Http\JsonResponse;

abstract class SocialLoginService
{
    protected GuzzleHttpService $guzzleHttpService;
    protected UserRepository $userRepository;
    protected OauthTokenRepository $oauthTokenRepository;
    protected SocialAccountRepository $socialAccountRepository;

    public function __construct() {
        $this->guzzleHttpService = new GuzzleHttpService();
        $this->userRepository = new UserRepository();
        $this->oauthTokenRepository = new OauthTokenRepository();
        $this->socialAccountRepository = new SocialAccountRepository();
    }

    /**
     * 구글 소셜 로그인 콜백 처리하는 메소드
     */
    abstract public function handleCallback();

    /**
     * 1) 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드 (User 없는 경우)
     * - User 생성, SocialAccount 생성, OauthToken 생성
     *
     * @param   string   $socialProvider  [$socialProvider description]
     * @param   TwoUser  $socialUser      [$socialUser description]
     *
     * @return  User
     */
    abstract public function handleNotUser(string $socialProvider, TwoUser $socialUser): User;

    /**
     * 2) 통합회원 전환을 처리하는 메소드
     *  - SocialAccount 생성, OauthToken 생성
     *
     * @param   int     $userId      User 테이블 PK
     * @param   array   $socialData  [social=>소셜 이름, socialUser=>[name=>소셜닉네임, email=>소셜이메일, provider_id=>소셜고유아이디, access_token=>소셜 액세스 토큰, refresh_token=>소셜 리프레쉬 토큰, expires_in=>소셜 만료]]
     * @return  JsonResponse
     */
    abstract public function handleLinkUserAccount(int $userId, array $socialData): JsonResponse;

    /**
     * 3) 소셜 회원가입으로 로그인 하는 경우를 처리하는 메소드
     * - User 있고 SocialAccount 있는 경우
     * - 만료기간 검사 후 OauthToken 업데이트
     *
     * @param   User  $user  users 테이블 Row
     *
     * @return  mixed
     */
    abstract public function handleSocialAccountsUser(User $user);

    /**
     * Refresh Token을 통해 Access Token을 다시 발급 받는 메소드
     *
     * @param   string  $refreshToken  Refresh Token
     *
     * @return  array
     */
    abstract public function refreshToken(string $refreshToken): array;
}
