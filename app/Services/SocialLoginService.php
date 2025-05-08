<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Constants\HttpCodeConstant;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\SocialAccountRepository;
use App\Repositories\Auth\OauthTokenRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Laravel\Socialite\Two\User as TwoUser;

class SocialLoginService
{
    protected GuzzleHttpService $guzzleHttpService;
    protected UserRepository $userRepository;
    protected OauthTokenRepository $oauthTokenRepository;
    protected SocialAccountRepository $socialAccountRepository;
    public string $socialProvider;

    public function __construct() {
        $this->guzzleHttpService = new GuzzleHttpService();
        $this->userRepository = new UserRepository();
        $this->oauthTokenRepository = new OauthTokenRepository();
        $this->socialAccountRepository = new SocialAccountRepository();
    }

    /**
     * 통합회원 전환 페이지로의 리턴을 처리하는 메소드
     *
     * @return  View
     */
    public function handleLinkUserAccount(TwoUser $socialUser, User $user): View
    {
        return view('user.link-account')->with([
            'userId' => $user->id,
            'socialData' => [
                'social' => $this->socialProvider,
                'socialUser' => [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'provider_id'   => $socialUser->getId(),
                    'access_token'  => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken,
                    'expires_in'    => $socialUser->expiresIn
                ]
            ],
            'linkAccount' => route('social.'.$this->socialProvider.'.link-account')
        ]);
    }

    /**
     * 통합회원 전환을 처리하는 메소드
     *  - SocialAccount 생성, OauthToken 생성
     *
     * @param   int           $userId      User 테이블 PK
     * @param   array         $socialData  [social=>소셜 이름, socialUser=>[name=>소셜닉네임, email=>소셜이메일, provider_id=>소셜고유아이디, access_token=>소셜 액세스 토큰, refresh_token=>소셜 리프레쉬 토큰, expires_in=>소셜 만료]]
     * @return  JsonResponse
     */
    public function linkUserAccount(int $userId, array $socialData): JsonResponse
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
     * 소셜 회원가입으로 최초 로그인 하는 경우를 처리하는 메소드
     * - User 생성, SocialAccount 생성, OauthToken 생성
     *
     * @param   TwoUser  $socialUser  소셜에서 제공하는 회원정보
     *
     * @return  User
     */
    public function handleNotUser(TwoUser $socialUser): User
    {
        $identifier = $socialUser->getEmail() ?? $socialUser->getId();
        $user = $this->userRepository->firstOrCreate(
            ['email' => $identifier],
            [
                'name'     => $socialUser->getName(),
                'email'    => $identifier,
                'password' => null
            ]
        );

        $socialAccount = $this->socialAccountRepository->firstOrCreate(
            [
                'user_id'       => $user->id,
                'provider_name' => $this->socialProvider,
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
     * 소셜 회원가입으로 로그인 하는 경우를 처리하는 메소드
     * - access_token 만료기간 검사 후 refresh_token 을 이용해 access_token 재발급
     *
     * @param   User  $user  users 테이블 row
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
     * refresh_token 을 이용해 access_token 을 재발급 받는 메소드
     *
     * @param   string  $refreshToken
     *
     * @return  array
     */
    public function refreshToken(string $refreshToken): array
    {
        $clientId = config('services.'.$this->socialProvider.'.client_id');
        $clientSecret = config('services.'.$this->socialProvider.'.client_secret');
        $refreshUri = config('services.'.$this->socialProvider.'.refresh');

        return $this->guzzleHttpService->postRequest(
            $refreshUri,
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
