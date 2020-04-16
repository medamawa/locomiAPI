<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistUserRequest;
use App\Models\User;
use App\Models\Activation;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Mail\ActivationCreated;

class RegisterController extends Controller
{
    private $userGuestRoleId;
    private $now;

    public function __construct()
    {
        
    }

    // 登録リクエストを受付
    public function register(RegistUserRequest $request)
    {
        $this->createActivation($request);

        return response()->json([
            'messages' => config('mail.message.send_verify_mail')
        ]);
    }

    // アクティベーションコードを生成して認証コードをメールで送信
    private function createActivation(RegistUserRequest $request)
    {
        $activation_code = Uuid::uuid4();

        $activation = new Activation;
        $activation->screen_name = $request->screen_name;
        $activation->name = $request->name;
        $activation->email = $request->email;
        $activation->password = bcrypt($request->password);
        $activation->code = $activation_code;
        $activation->save();

        Mail::to($activation->email)->send(new ActivationCreated($activation));
    }


    // メール認証コードを検証してユーザー情報の登録
    public function verify(Request $request)
    {
        $code = $request->code;

        //認証確認
        if (!$this->checkCode($code)) {

            return response()->json(config('error.mailActivationError'));
        } else {
            // ユーザー情報の登録
            DB::beginTransaction();
            try {
                $activation = Activation::where('code', $code)->first();

                $user = new User();
                $user->screen_name = $activation->screen_name;
                $user->name = $activation->name;
                $user->email = $activation->email;
                $user->password = $activation->password;
                $user->save();
                Activation::where('code', $code)->update(['email_verified_at' => Carbon::now()]);

                DB::commit();
                return response()->json(config('mail.message.add_user_success'));                           // ブラウザだと文字化けするので対策必要
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('WEB /user/verify - Class ' . get_class() . ' -PDOException Error. Rollback was executed.' . $e->getMessage());

                return response()->json(config('error.databaseTransactionRollback'));
            }
        }
    }

    // ** メールの認証コードの検証
    // 1. 与えられた認証コードがActivations.codeに存在するか？
    // 2. activations.emailが存在し、ユーザー登録が既に完了したメールアドレスかどうか？
    // 3. 認証コード発行後1日以内に発行された認証コードであるか？
    private function checkCode($code)
    {
        $activation = Activation::where('code', $code)->first();
        if(!$activation) {
            return false;
        }

        $activation_email = $activation->email;
        $latest = Activation::where('email', $activation_email)->orderBy('created_at', 'desc')->first();
        
        $user = User::where('email', $activation_email)->first();
        $activation_created_at = Carbon::parse($activation->created_at);
        $expire_at = $activation_created_at->addDay(1);
        $now = Carbon::now();

        return $code == $latest->code && !$user && $now->lt($expire_at);
    }
}
