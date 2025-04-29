<?php

namespace Tests\Unit\Social\Login;

use App\Constants\SocialConstant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Socialite\Two\User as TwoUser;
use App\Services\Social\Login\GoogleService;
use App\Models\User;

class GoogleServiceTest extends TestCase
{
    protected GoogleService $googleService;
    protected TwoUser $socialUser;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->googleService = new GoogleService();
        $this->socialUser = (new TwoUser())
        ->setToken('ya29.a0AZYkNZjlgL1-54AbbR5pkXyUyF_YcAdAOg0175')
        ->setRefreshToken('1//0eDktIegYfTysakXCyA')
        ->setExpiresIn(3599)
        ->setApprovedScopes([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'openid',
        ])
        ->setRaw([
            'id' => '112954201399',
            'nickname' => null,
            'name' => '테스터',
            'email' => 'tester@gmail.com',
            'avatar' => 'https://lh3.googleusercontent.com/a/A6-c',
            'user' => [
                'sub' => '112954201399',
                'name' => '테스터',
                'given_name' => '스터',
                'family_name' => '테',
                'picture' => 'https://lh3.googleusercontent.com/a/A6-c',
            ],
            'attributes' => [
                'id' => '112954399',
                'nickname' => null,
                'name' => '테스터',
                'email' => 'tester@gmail.com',
                'avatar' => 'https://lh3.googleusercontent.com/a/A6-c',
                'avatar_original' => 'https://lh3.googleusercontent.com/a/A6-c',
            ],
        ])
        ->map([
            'id' => '112954201399',
            'nickname' => null,
            'name' => '테스터',
            'email' => 'tester@gmail.com',
            'avatar' => 'https://lh3.googleusercontent.com/a/A6-c',
            'avatar_original' => 'https://lh3.googleusercontent.com/a/A6-c',
        ]);
    }

    /**
     * 테스트: 1) 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드
     *
     * @return  void
     */
    public function test_handle_not_user()
    {
        $user = $this->googleService->handleNotUser(SocialConstant::GOOGLE, $this->socialUser);

        // 1. 반환된 객체가 User 타입인지 확인
        $this->assertInstanceOf(User::class, $user);

        // 2. User 객체의 값을 확인
        $this->assertEquals('테스터', $user->name);
        $this->assertEquals('tester@gmail.com', $user->email);
    }

    public function test_handle_link_user_account()
    {
    }
}
