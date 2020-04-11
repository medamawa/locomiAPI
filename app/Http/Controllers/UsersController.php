<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function follow(User $user)
    {
        $follower = auth()->user();
        $all_friends = $user->getAllFriends(auth()->user()->id);

        $is_following = $follower->isFollowing($user->id);
        if (!$is_following) {
            $follower->follow($user->id);
            return response()->json(['all_friends' => $all_friends]);
        }
    }

    public function unfollow(User $user)
    {
        $follower = auth()->user();
        $all_friends = $user->getAllFriends(auth()->user()->id);

        $is_following = $follower->isFollowing($user->id);
        if ($is_following) {
            $follower->unfollow($user->id);
            return response()->json(['all_friends' => $all_friends]);
        }
    }
}
