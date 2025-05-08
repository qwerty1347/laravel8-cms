<?php

namespace Tests\Unit\Social\Login;

use Exception;
use Tests\TestCase;
use App\Constants\HttpCodeConstant;
use App\Models\User;
use App\Services\SocialLoginService;
use App\Services\Social\Login\KakaoService;
use App\Repositories\UserRepository;
use Laravel\Socialite\Two\User as TwoUser;
class KakaoServiceTest extends TestCase
{
    public const NAME = "카카오테스터";
    public const EMAIL = "4250947549";
    public const NICKNAME = "테스터";
    public const PROVIDER_ID = "4250947549";
    public const ACCESS_TOKEN = "sSZAW6Abu1N11N0Lm";
    public const REFRESH_TOKEN = "PHt7o-nPouTk2e0q";

    protected SocialLoginService $socialLoginService;
    protected KakaoService $kakaoService;
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
        $this->kakaoService = new KakaoService();
        $this->userRepository = new UserRepository();
        $this->socialUser = (new TwoUser())
        ->setToken(self::ACCESS_TOKEN)
        ->setRefreshToken(self::REFRESH_TOKEN)
        ->setExpiresIn(21599)
        ->setRaw([
            'access_token' => self::ACCESS_TOKEN,
            'refresh_token' => self::REFRESH_TOKEN,
            'token_type' => 'bearer',
            'expires_in' => 21599,
            'refresh_token_expires_in' => 5183999,
            'scope' => 'profile_image profile_nickname',
            'id' => self::PROVIDER_ID,
            'nickname' => self::NICKNAME,
            'name' => self::NAME,
            'email' => self::EMAIL,
            'avatar' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_640x640.jpg',
            'user' => [
                'id' => self::PROVIDER_ID,
                'connected_at' => '2021-10-23T15:25:41Z',
                'properties' => [
                    'nickname' => self::NICKNAME,
                    'profile_image' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_640x640.jpg',
                    'thumbnail_image' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_110x110.jpg'
                ],
                'kakao_account' => [
                    'profile_nickname_needs_agreement' => false,
                    'profile_image_needs_agreement' => false,
                    'profile' => [
                        'nickname' => self::NICKNAME,
                        'thumbnail_image_url' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_110x110.jpg',
                        'profile_image_url' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_640x640.jpg',
                        'is_default_image' => false,
                        'is_default_thumbnail_image' => false
                    ],
                ],
            ],
            'attributes' => [
                'id' => self::PROVIDER_ID,
                'nickname' => self::NICKNAME,
                'name' => self::NAME,
                'email' => self::EMAIL,
                'avatar' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_640x640.jpg'
            ],
        ])
        ->map([
            'id' => self::PROVIDER_ID,
            'nickname' => self::NICKNAME,
            'name' => self::NAME,
            'email' => self::EMAIL,
            'avatar' => 'http://k.kakaocdn.net/dn/bnXDNA/b/img_640x640.jpg'
        ]);
    }

    /**
     * 테스트: 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드
     */
    public function test_handle_kakao_not_user()
    {
        $user = $this->kakaoService->handleNotUser($this->socialUser);

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
    public function test_handle_kakao_link_user_account()
    {
        $response = $this->socialLoginService->linkUserAccount(
            User::max('id'),
            [
                'social' => $this->kakaoService->socialProvider,
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

    public function test_kakao_refresh_token()
    {
        try {
            $this->socialLoginService->socialProvider = $this->kakaoService->socialProvider;
            $response = $this->socialLoginService->refreshToken(self::REFRESH_TOKEN);
            dd($response);

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
