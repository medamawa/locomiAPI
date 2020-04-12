<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;

class ComicsController extends Controller
{
    public function index(Comic $comic)
    {
        $comics = $comic->getPublicComics();

        return response()->json(['all_public_comics' => $comics]);
        
        // フォローしているアカウントの投稿のみを表示（ログイン時）
        // 
        // ... index(Comic $comic, Friend $friend)
        // 
        // $user = auth()->user();
        // $follow_ids = $friend->followedIds($user->id);
        // $followed_ids = $follow_ids->pluck('followed_id')->toArray();

        // $follows_comics = $comic->getFollowsComics($user->id, $followed_ids);
    }

    public function create()
    {
        //
    }

    public function store(Request $request, Comic $comic)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'text' => ['required', 'string', 'max:140'],
            'release' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }

        $user = auth()->user();
        $location = ['latitude' => $data['lat'], 'longitude' => $data['lng']];
        // $dataにlocation配列を追加する
        $data['location'] = $location;
        $comic->comicStore($user->id, $data);

        return response()->json([
            'success' => 'Comiced',
            'comic' => $data,
            ]);
    }

    public function show(Comic $comic, Comment $comment,Favorite $favorite, String $id)
    {
        $user = auth()->user();
        $comic = $comic->getComic($id);
        $comments = $comment->getComments($id);
        $favorites = $favorite->getFavorites($id);

        return response()->json([
            'user' => $user,
            'comic' => $comic,
            'comments' => $comments,
            'favorites' => $favorites,
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Comic $comic, String $id)
    {
        $user = auth()->user();
        $comic->comicDestroy($user->id, $id);

        return response()->json(['success' => 'Deleted']);
    }
}
