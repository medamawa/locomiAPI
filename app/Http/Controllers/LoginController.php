<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiTokenCreateService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Http\Requests\LoginRequest;


class LoginController extends Controller
{
    // ログインの実行
    public function login(LoginRequest $request)
    {
        $data = $request->only('email', 'password');
        $token = null;

        if (!$token = auth()->attempt($data)) {
            return response()->json([
                'status' => 'error',
                'auth_message' => 'Unauthorized',
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        $ApiTokenCreateService = new ApiTokenCreateService($user);

        return $ApiTokenCreateService->respondWithToken();
    }

}
