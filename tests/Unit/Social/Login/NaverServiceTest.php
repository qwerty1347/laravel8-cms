<?php

namespace Tests\Unit\Social\Login;

use Exception;
use Tests\TestCase;
use App\Constants\HttpCodeConstant;
use App\Models\User;
use App\Services\SocialLoginService;
use App\Services\Social\Login\NaverService;
use App\Repositories\UserRepository;
use Laravel\Socialite\Two\User as TwoUser;

class NaverServiceTest extends TestCase
{
    public const NAME = "네이버테스터";
    public const EMAIL = "tester@naver.com";
    public const NICKNAME = "테스터";
    public const PROVIDER_ID = "GdPpdGGK8GHr1";
    public const ACCESS_TOKEN = "AAAAOrIrJK8vxFbv5";
    public const REFRESH_TOKEN = "ojSpAbcvrQseDYDZ4H";

    protected SocialLoginService $socialLoginService;
    protected NaverService $naverService;
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
        $this->naverService = new NaverService();
        $this->userRepository = new UserRepository();
        $this->socialUser = (new TwoUser())
        ->setToken(self::ACCESS_TOKEN)
        ->setRefreshToken(self::REFRESH_TOKEN)
        ->setExpiresIn(3600)
        ->setApprovedScopes([])
        ->setRaw([
            'id' => self::PROVIDER_ID,
            'nickname' => self::NICKNAME,
            'name' => self::NAME,
            'email' => self::EMAIL,
            'avatar' => null,
            'user' => [
                'sub' => self::ACCESS_TOKEN,
                'nickname' => self::NICKNAME,
                'name' => self::NAME,
                'email' => self::EMAIL,
            ],
            'attributes' => [
                'id' => self::PROVIDER_ID,
                'nickname' => self::NICKNAME,
                'name' => self::NAME,
                'email' => self::EMAIL,
                'avatar' => null,
            ],
        ])
        ->map([
            'id' => self::PROVIDER_ID,
            'nickname' => self::NICKNAME,
            'name' => self::NAME,
            'email' => self::EMAIL,
            'avatar' => null,
            'avatar_original' => null,
        ]);
    }

        /**
     * 테스트: 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드
     */
    public function test_handle_naver_not_user()
    {
        $user = $this->naverService->handleNotUser($this->socialUser);

        // 1. 반환된 객체가 User 타입 검증
        $this->assertInstanceOf(User::class, $user);

        // 2. User 검증
        $this->assertEquals(self::NAME, $user->name);
        $this->assertEquals(self::EMAIL, $user->email);
    }

    /**
     * 테스트: 통합회원 전환을 처리하는 메소드
     *  - SocialAccount 생성, OauthToken 생성
     */
    public function test_handle_naver_link_user_account()
    {
        $response = $this->socialLoginService->linkUserAccount(
            User::max('id'),
            [
                'social' => $this->naverService->socialProvider,
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

        // 1. HTTP 상태 코드 검증
        $this->assertEquals(HttpCodeConstant::OK, $response->getStatusCode());
    }

    /**
     * 테스트: refresh_token 을 이용해 access_token 을 재발급 받는 메소드
     */
    public function test_naver_refresh_token()
    {
        try {
            $this->socialLoginService->socialProvider = $this->naverService->socialProvider;
            $response = $this->socialLoginService->refreshToken(self::REFRESH_TOKEN);
            dd($response);

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
