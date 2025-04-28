<?php

namespace App\Services\Social\Login;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Laravel\Socialite\Two\User as TwoUser;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\SocialConstant;
use App\Constants\HttpCodeConstant;
use App\Repositories\UserRepository;
use App\Repositories\SocialAccountRepository;
use App\Repositories\Auth\OauthTokenRepository;
use Illuminate\Http\JsonResponse;
use App\Services\SocialLoginService;
use App\Services\GuzzleHttpService;

class NaverService extends SocialLoginService
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
     * 네이버 소셜 로그인 콜백 처리하는 메소드
     *
     * 1. User 없는 경우
     *  - 생성 User 생성(email란에 고유아이디 값으로), SocialAccount 생성, OauthToken 생성
     *  - 로그인
     *
     * 2. User 있고 SocialAccount 없는 경우
     *  - 블레이드 페이지로 이동 (통합 하시겠습니까?)
     *  - Y: SocialAccount 생성, OauthToken 생성
     *  - N: 가입된 회원아이디로 로그인해 주세요.
     *
     * 3. User 있고 SocialAccount 있는 경우
     *  - 로그인
     *
     * @return  [type]  [return description]
     */
    public function handleCallback()
    {
        try {
            $socialUser = Socialite::driver('naver')->stateless()->user();
            $user = $this->userRepository->getUserWithSocialAccountRow($socialUser->getId());

            if (!isset($user)) {
                $user = $this->handleNotUser(SocialConstant::NAVER, $socialUser);
            }
            else if (isset($user) && $user->socialAccounts->isEmpty()) {
                // 2)
            }
            else {
                // 3)
            }
        }

        catch (Exception $e) {
            DB::rollBack();
            $logMessage = "#1 ".$e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
            logMessage('adminlog', 'error', $logMessage);

            dd($e, $e->getMessage(), $e->getFile(), $e->getLine());

            return redirect('login');
        }
    }

    /**
     * 1) 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드 (User 없는 경우)
     * - User 생성, SocialAccount 생성, OauthToken 생성
     *
     * @param   string   $socialProvider  [$socialProvider description]
     * @param   TwoUser  $socialUser      [$socialUser description]
     *
     * @return  User
     */
    public function handleNotUser(string $socialProvider, TwoUser $socialUser): User
    {
        // TODO: 여기 작업 해야 함 !
        dd(0);

        $user = $this->userRepository->firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'     => $socialUser->getName(),
                'email'    => $socialUser->getEmail(),
                'password' => null
            ]
        );

        $socialAccount = $this->socialAccountRepository->firstOrCreate(
            [
                'user_id'       => $user->id,
                'provider_name' => $socialProvider,
                'provider_id'   => $socialUser->getId()
            ]
        );

        $this->oauthTokenRepository->updateOrCreate(
            [
                'user_id'           => $user->id,
                'social_account_id' => $socialAccount->id,
            ],
            [
                'access_token'  => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
                'expires_at'    => now()->addSeconds($socialUser->expiresIn)
            ]
        );

        return $user;
    }
    /**
     * 2) 통합회원 전환을 처리하는 메소드
     *  - SocialAccount 생성, OauthToken 생성
     *
     * @param   int     $userId      User 테이블 PK
     * @param   array   $socialData  [social=>소셜 이름, socialUser=>[name=>소셜닉네임, email=>소셜이메일, provider_id=>소셜고유아이디, access_token=>소셜 액세스 토큰, refresh_token=>소셜 리프레쉬 토큰, expires_in=>소셜 만료]]
     * @return  JsonResponse
     */
    public function handleLinkUserAccount(int $userId, array $socialData): JsonResponse
    {
        dd(2);
    }

    /**
     * 3) 소셜 회원가입으로 로그인 하는 경우를 처리하는 메소드
     * - User 있고 SocialAccount 있는 경우
     * - 만료기간 검사 후 OauthToken 업데이트
     *
     * @param   User  $user  users 테이블 Row
     *
     * @return  mixed
     */
    public function handleSocialAccountsUser(User $user)
    {
        dd(3);
    }

    /**
     * Refresh Token을 통해 Access Token을 다시 발급 받는 메소드
     *
     * @param   string  $refreshToken  Refresh Token
     *
     * @return  array
     */
    public function refreshToken(string $refreshToken): array
    {
        dd(4);
    }
}
