<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckRefreshToken
{
    // リクエストのヘッダーで送られてくるトークンを検証
    public function handle($request, Closure $next)
    {
        $string = $token = JWTAuth::getToken();

        // ヘッダーにAuthorizationが存在するかチェック
        if (!$request->header('Authorization')) {

            return response()->json([
                'status' => 'error',
                'messages' => Config::get('error.headerAuthorizationMissing'),
            ]);
        }

        try {
            // トークンに含まれているユーザーは存在するかチェック
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json([
                    'ststus' => 'error',
                    'messages' => Config::get('error.invalidToken'),
                ]);
            }
        } catch (TokenInvalidException $e) {
            // 無効なリフレッシュトークンによるエラー
            return response()->json([
                'status' => 'error',
                'messages' => Config::get('error.invalidRefreshToken'),
            ]);
        } catch (TokenExpiredException $e) {
            // リフレッシュトークンの有効期限切れによるエラー
            return response()->json([
                'status' => 'error',
                'messages' => Config::get('error.expiredRefreshToken')
            ]);
        } catch (JWTException $e) {
            // その他の原因によるトークンのエラー
            return response()->json([
                'status' => 'error',
                'messages' => Config::get('error.tokenSomethingWentWrongError')
            ]);
        }
        // コントローラーにリクエストを送る
        $response = $next($request);

        return $response;
    }
}
