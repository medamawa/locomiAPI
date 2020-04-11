<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class UsersController extends Controller
{
    public function index(User $user)
    {
        // ログインしていればそのアカウントを除く、そうでなければ全てのアカウントを返す
        if (isset(auth()->user()->id)) {
            $all_users = $user->getAllUsers(auth()->user()->id);
        } else {
            $all_users = $user->getAllUsers("");
        }

        return response()->json(['all_users' => $all_users]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user, String $id)
    {
        $user = $user->getUser($id);

        return response()->json(['user' => $user]);
    }

    public function edit($id)
    {
        //
    }

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

        $user->updateProfile($data);

        return response()->json(['success' => 'Updated']);
    }

    public function destroy($id)
    {
        //
    }

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
            return response()->json(['success' => 'Followed']);
        } else {
            $user->unfollow($followed);
            return response()->json(['success' => 'Unfollowed']);
        }
    }

    public function follows()
    {
        $user = auth()->user();
        $all_follows = $user->getAllFollows(auth()->user()->id);

        return response()->json(['all_follows' => $all_follows]);
    }

    public function followers()
    {
        $user = auth()->user();
        $all_followers = $user->getAllFollowers(auth()->user()->id);

        return response()->json(['all_followers' => $all_followers]);
    }
}
