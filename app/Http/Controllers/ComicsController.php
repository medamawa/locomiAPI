<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ComicsRequest;

class ComicsController extends Controller
{
    // 全ての投稿の一覧情報を返す
    public function index(Comic $comic)
    {
        $comics = $comic->getPublicComics();

        return response()->json($comics);
        
        // フォローしているアカウントの投稿のみを表示（ログイン時）
        // 
        // ... index(Comic $comic, Friend $friend)
        // 
        // $user = auth()->user();
        // $follow_ids = $friend->followedIds($user->id);
        // $followed_ids = $follow_ids->pluck('followed_id')->toArray();

        // $follows_comics = $comic->getFollowsComics($user->id, $followed_ids);
    }

    // 投稿をする
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

        return response()->json($data);
    }

    // 指定した投稿を返す
    public function show(Comic $comic, Comment $comment, Favorite $favorite, String $id)
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

    // 投稿を消去
    public function destroy(Comic $comic, String $id)
    {
        $user = auth()->user();
        $comic->comicDestroy($user->id, $id);

        return response()->json(['success' => 'Deleted']);
    }
}
