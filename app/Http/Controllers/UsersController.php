<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class UsersController extends Controller
{
    // 全てのユーザーの一覧情報を返す
    public function index(User $user)
    {
        // ログインしていればそのアカウントを除く、そうでなければ全てのアカウントを返す
        if (isset(auth()->user()->id)) {
            $all_users = $user->getAllUsers(auth()->user()->id);
        } else {
            $all_users = $user->getAllUsers("");
        }

        return response()->json($all_users);
    }

    // 指定したユーザーの情報を返す
    public function show(User $user, String $id)
    {
        $user = $user->getUser($id);

        return response()->json($user);
    }

    // ユーザーの情報を編集する（保留中）
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'screen_name' => ['string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }

        $data['id'] = $user->id;

        $id = $user->updateProfile($data);

        // return response()->json(['success' => 'Updated']);
        return response()->json(['success' => $id]);

    }

    public function destroy($id)
    {
        //
    }

    // 指定したユーザーをフォローする
    public function follow(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => ['required', 'string', 'uuid'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }
        
        $user = auth()->user();
        $followed = $request->id;

        $is_following = $user->isFollowing($followed);
        if (!$is_following) {
            $user->follow($followed);
            return response()->json([
                'status' => 'success',
                'message' => 'Followed',
                ]);
        } else {
            $user->unfollow($followed);
            return response()->json([
                'status' => 'success',
                'message' => 'Unfollowed'
                ]);
        }
    }

    // ログインしているユーザーがフォローしているユーザーの一覧を返す
    public function follows()
    {
        $user = auth()->user();
        $all_follows = $user->getAllFollows(auth()->user()->id);

        return response()->json([
            'status' => 'success',
            'data' => $all_follows,
        ]);
    }

    // ログインしているユーザーのフォロワーの一覧を返す
    public function followers()
    {
        $user = auth()->user();
        $all_followers = $user->getAllFollowers(auth()->user()->id);

        return response()->json([
            'status' => 'success',
            'data' => $all_followers,
        ]);
    }
}
