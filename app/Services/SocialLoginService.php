<?php

namespace App\Services;

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

class SocialLoginService
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

    public function handleGoogleCallback()
    {
        DB::beginTransaction();

        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
            $user = $this->userRepository->getUserWithSocialAccountRow($socialUser->getEmail(), $socialUser->getId());


// // TODO:
/**
 *
 * 1. User 없는 경우
 *  - 생성 User 생성, SocialAccount 생성, OauthToken 생성
 *  - 로그인
 *
 * 2. User 있고 SocialAccount 없는 경우
 *  - 블레이드 페이지로 이동 (통합 하시겠습니까?)
 *  - Y: SocialAccount 생성, OauthToken 생성
 *  - N: 가입된 회원아이디로 로그인해 주세요.
 *
 * 3. User 있고 SocialAccount 있는 경우
 *  - 로그인
 */

            if (!isset($user)) {
                $user = $this->handleNotUser(SocialConstant::GOOGLE, $socialUser);
            }
            else if (isset($user) && $user->socialAccounts->isEmpty()) {
                return view('user.link-account');
            }
            else {
                $this->handleSocialAccountsUser($user, $user->socialAccounts);
            }

            DB::commit();


            // dd("done", $user);


            Auth::login($user, true);

            return redirect()->to('/'); // 로그인 후 리디렉션할 URL 설정

        } catch (Exception $e) {
            DB::rollBack();
            $logMessage = $e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
            logMessage('adminlog', 'error', $logMessage);

            dd($e, $e->getMessage(), $e->getFile(), $e->getLine());
            return redirect('login');
        }

    }

    /**
     * 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드 (User 없는 경우)
     * - User 생성, SocialAccount 생성, OauthToken 생성
     *
     * @param   string   $socialProvider  [$socialProvider description]
     * @param   TwoUser  $socialUser      [$socialUser description]
     *
     * @return  User                      [return description]
     */
    public function handleNotUser(string $socialProvider, TwoUser $socialUser): User
    {
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

    public function handleNotSocialAccountsUser()
    {
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
            $user = $this->handleNotUser(SocialConstant::GOOGLE, $socialUser);

            Auth::login($user, true);

            return redirect()->to('/admin'); // 로그인 후 리디렉션할 URL 설정

        } catch (Exception $e) {
            dd("오류", $e->getMessage());
        }


        dd($user);
    }

    /**
     * 소셜 회원가입으로 로그인 하는 경우를 처리하는 메소드
     * - User 있고 SocialAccount 있는 경우
     * - 만료기간 검사 후 OauthToken 업데이트
     *
     * @param   User  $user  users 테이블 Row
     *
     * @return  mixed
     */
    public function handleSocialAccountsUser(User $user)
    {
        $oauthToken = $this->oauthTokenRepository->firstWhere([
            'user_id'           => $user->id,
            'social_account_id' => $user->getSocialAccountsRow()->id
        ]);

        if (!isset($oauthToken)) {
            throw new Exception('토큰이 존재하지 않습니다.', HttpCodeConstant::UNAUTHORIZED);
        }

        if ($oauthToken && Carbon::now()->greaterThan($oauthToken->expires_at)) {
            $response = $this->refreshGoogleToken($oauthToken->refresh_token);
            $this->oauthTokenRepository->update(
                ['id' => $oauthToken->id],
                [
                    'access_token' => $response['data']['access_token'],
                    'expires_at' => now()->addSeconds($response['data']['expires_in'])
                ]
            );
        }
    }

    /**
     * Refresh Token으로 Google Access Token을 다시 발급 받는 메소드
     *
     * @param   string  $refreshToken  Refresh Token
     *
     * @return  array
     */
    private function refreshGoogleToken(string $refreshToken): array
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        return $this->guzzleHttpService->postRequest(
            'https://oauth2.googleapis.com/token',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type'    => 'refresh_token'
            ]
        );
    }
}
