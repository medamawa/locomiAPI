<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiTokenCreateService;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class RefreshTokenController extends Controller
{
    // private $user;
    // private $user_id;
    // private $access_token;

    public function __construct()
    {
        $this->middleware('auth:api', ['expect' => ['login']]);
    }

    // アクセストークンのリフレッシュ
    public function refreshToken()
    {
        // $this->access_token = auth()->refresh();
        // $this->user_id = auth()->user()->id;
        // $this->user = User::where('id', $this->user_id)->first();

        // return $this->respondWithToken();

        $user = auth()->user();
        $ApiTokenCreateService = new ApiTokenCreateService($user);

        return $ApiTokenCreateService->respondWithToken();
    }

    // トークンとユーザー情報のJSONデータを返却
    public function respondWithToken()
    {
        return response()->json([
            'token' => [
                'access_token' => $this->access_token,
            ],
            'profile' => [
                'id' => $this->user->id,
                'screen_name' => $this->user->screen_name,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
        ]);
    }
}
