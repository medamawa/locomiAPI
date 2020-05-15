<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class ApiTokenCreateService extends Service
{
    protected $user;
    protected $now;

    public function __construct(User $user)
    {
        $this->user = $user;
        $carbon = new Carbon();
        $this->now = $carbon->now()->timestamp;
    }

    // トークンとユーザー情報のJSONデータを返却
    public function respondWithToken()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $this->user->id,
                'access_token' => $this->createAccessToken(),
                'refresh_token' => $this->createRefreshToken(),
            ],
        ]);
    }

    // API用のアクセストークンを作成
    public function createAccessToken()
    {
        $customClaims = $this->getJWTCustomClaimsForAccessToken();
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload)->get();

        return $token;
    }

    // API用のリフレッシュトークンを作成
    public function createRefreshToken()
    {
        $customClaims = $this->getJWTCustomClaimsForRefreshToken();
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload)->get();

        return $token;
    }

    // アクセストークン用CustomClaimsを返却
    public function getJWTCustomClaimsForAccessToken()
    {
        $data = [
            'sub' => $this->user->id,
            'iat' => $this->now,
            'exp' => $this->now + config('token.expire.accessToken')
        ];

        return JWTFactory::customClaims($data);
    }

    // リフレッシュ用CustomClaimsを返却
    public function getJWTCustomClaimsForRefreshToken()
    {
        $data = [
            'sub' => $this->user->id,
            'iat' => $this->now,
            'exp' => $this->now + config('token.expire.refreshToken')
        ];

        return JWTFactory::customClaims($data);
    }
}
