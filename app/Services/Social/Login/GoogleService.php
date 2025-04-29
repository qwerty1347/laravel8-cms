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

class GoogleService extends SocialLoginService
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
     *
     * @return  mixed (redirect|view)
     */
    public function handleCallback()
    {
        try {
            DB::beginTransaction();
            $socialUser = Socialite::driver('google')->stateless()->user();
            $user = $this->userRepository->getUserWithSocialAccountRow($socialUser->getEmail(), $socialUser->getId());

            if (!isset($user)) {
                $user = $this->handleNotUser(SocialConstant::GOOGLE, $socialUser);
            }
            else if (isset($user) && $user->socialAccounts->isEmpty()) {

                return view('user.link-account')->with([
                    'userId' => $user->id,
                    'socialData' => [
                        'social' => SocialConstant::GOOGLE,
                        'socialUser' => [
                            'name'          => $socialUser->getName(),
                            'email'         => $socialUser->getEmail(),
                            'provider_id'   => $socialUser->getId(),
                            'access_token'  => $socialUser->token,
                            'refresh_token' => $socialUser->refreshToken,
                            'expires_in'    => $socialUser->expiresIn
                        ]
                    ],
                    'linkAccount' => route('social.google.link-account')
                ]);
            }
            else {
                $this->handleSocialAccountsUser($user, $user->socialAccounts);
            }

            Auth::login($user, true);
            DB::commit();

            return redirect()->to('/admin');

        } catch (Exception $e) {
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
     * @param   string   $socialProvider  소셜 이름
     * @param   TwoUser  $socialUser
     *
     * @return  User
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
        try {
            DB::beginTransaction();
            $socialAccount = $this->socialAccountRepository->firstOrCreate(
                [
                    'user_id'       => $userId,
                    'provider_name' => $socialData['social'],
                    'provider_id'   => $socialData['socialUser']['provider_id']
                ]
            );

            $this->oauthTokenRepository->updateOrCreate(
                [
                    'user_id'           => $userId,
                    'social_account_id' => $socialAccount->id
                ],
                [
                    'access_token'  => $socialData['socialUser']['access_token'],
                    'refresh_token' => $socialData['socialUser']['refresh_token'],
                    'expires_at'    => now()->addSeconds($socialData['socialUser']['expires_in'])
                ]
            );

            $user = $this->userRepository->getUserWithSocialAccountRow($socialData['socialUser']['email'], $socialData['socialUser']['provider_id']);

            Auth::login($user, true);
            DB::commit();

            return response()->json(handleSuccessResult(), HttpCodeConstant::OK, [], JSON_UNESCAPED_UNICODE);
        }
        catch (Exception $e) {
            DB::rollBack();
            $logMessage = "#00 ".$e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
            logMessage('adminlog', 'error', $logMessage);
            return response()->json(handleFailureResult(HttpCodeConstant::INTERVAL_SERVER_ERROR, $e->getMessage()), HttpCodeConstant::INTERVAL_SERVER_ERROR, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 3) 소셜 회원가입으로 로그인 하는 경우를 처리하는 메소드
     * - User 있고 SocialAccount 있는 경우
     * - 만료기간 검사 후 OauthToken 업데이트
     *
     * @param   User  $user
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
            $response = $this->refreshToken($oauthToken->refresh_token);
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
     * Refresh Token을 통해 Access Token을 다시 발급 받는 메소드
     *
     * @param   string  $refreshToken  Refresh Token
     *
     * @return  array
     */
    public function refreshToken(string $refreshToken): array
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
