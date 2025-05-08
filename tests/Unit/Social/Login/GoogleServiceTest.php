<?php

namespace Tests\Unit\Social\Login;

use Exception;
use Tests\TestCase;
use App\Constants\HttpCodeConstant;
use App\Models\User;
use App\Services\SocialLoginService;
use App\Services\Social\Login\GoogleService;
use App\Repositories\UserRepository;
use Laravel\Socialite\Two\User as TwoUser;

class GoogleServiceTest extends TestCase
{
    public const NAME = "구글테스터";
    public const EMAIL = "tester@gmail.com";
    public const NICKNAME = "테스터";
    public const PROVIDER_ID = "112954201399";
    public const ACCESS_TOKEN = "ya29.a0AZYkNZjlgL1-54AbbR5pkXyUyF_YcAdAOg0175";
    public const REFRESH_TOKEN = "1//0eDktIegYfTysakXCyA";

    protected SocialLoginService $socialLoginService;
    protected GoogleService $googleService;
    protected UserRepository $userRepository;
    protected User $user;
    protected TwoUser $socialUser;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->socialLoginService = new SocialLoginService();
        $this->googleService = new GoogleService();
        $this->userRepository = new UserRepository();
        $this->socialUser = (new TwoUser())
        ->setToken(self::ACCESS_TOKEN)
        ->setRefreshToken(self::REFRESH_TOKEN)
        ->setExpiresIn(3599)
        ->setApprovedScopes([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'openid',
        ])
        ->setRaw([
            'id' => self::PROVIDER_ID,
            'nickname' => null,
            'name' => self::NAME,
            'email' => self::EMAIL,
            'avatar' => 'https://lh3.googleusercontent.com/a/A6-c',
            'user' => [
                'sub' => self::PROVIDER_ID,
                'name' => self::NAME,
                'given_name' => '스터',
                'family_name' => '테',
                'picture' => 'https://lh3.googleusercontent.com/a/A6-c',
            ],
            'attributes' => [
                'id' => self::PROVIDER_ID,
                'nickname' => null,
                'name' => self::NAME,
                'email' => self::EMAIL,
                'avatar' => 'https://lh3.googleusercontent.com/a/A6-c',
                'avatar_original' => 'https://lh3.googleusercontent.com/a/A6-c',
            ],
        ])
        ->map([
            'id' => self::PROVIDER_ID,
            'nickname' => null,
            'name' => self::NAME,
            'email' => self::EMAIL,
            'avatar' => 'https://lh3.googleusercontent.com/a/A6-c',
            'avatar_original' => 'https://lh3.googleusercontent.com/a/A6-c',
        ]);
    }

    /**
     * 테스트: 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드
     */
    public function test_handle_google_not_user()
    {
        $user = $this->googleService->handleNotUser($this->socialUser);

        // 반환된 객체 타입 검증
        $this->assertInstanceOf(User::class, $user);

        // 반환된 객체 프로퍼티 검증
        $this->assertEquals(self::NAME, $user->name);
        $this->assertEquals(self::EMAIL, $user->email);
    }

    /**
     * 테스트: 통합회원 전환을 처리하는 메소드
     *  - SocialAccount 생성, OauthToken 생성
     */
    public function test_handle_google_link_user_account()
    {
        $response = $this->socialLoginService->linkUserAccount(
            User::max('id'),
            [
                'social' => $this->googleService->socialProvider,
                'socialUser' => [
                    'name' => self::NAME,
                    'email' => self::EMAIL,
                    'provider_id' => self::PROVIDER_ID,
                    'access_token' => self::ACCESS_TOKEN,
                    'refresh_token' => self::REFRESH_TOKEN,
                    'expires_in' => 3599
                ]
            ]
        );

        // HTTP 상태 코드 검증
        $this->assertEquals(HttpCodeConstant::OK, $response->getStatusCode());
    }

    /**
     * 테스트: refresh_token 을 이용해 access_token 을 재발급 받는 메소드
     */
    public function test_google_refresh_token()
    {
        try {
            $this->socialLoginService->socialProvider = $this->googleService->socialProvider;
            $response = $this->socialLoginService->refreshToken(self::REFRESH_TOKEN);
            dd($response);

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
