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

    // 特定のユーザーの投稿の一覧情報を返す
    public function index_user(Comic $comic, String $user_id)
    {
        $comics = $comic->getUserComics($user_id);

        return response()->json($comics);
    }

    // 近く(半径１km以内)の投稿を返す
    public function index_near(Request $request, Comic $comic)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }

        $comics = $comic->getNearComics($data['lat'], $data['lng']);

        return response()->json($comics);
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
            'altitude' => ['numeric']   // 数値かどうか
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
            'status' => 'success',
            'data' => $data,
        ]);
    }

    // 指定した投稿を返す
    public function show(Comic $comic, Comment $comment, Favorite $favorite, String $id)
    {
        // 消去されていないことを確認
        if(!$this->checkPost($id)) {
            return  response()->json([
                'status' => 'error',
                'message' => 'already has deleted'
            ]);
        }
        
        $comic = $comic->getComic($id);
        $comments = $comment->getComments($id);
        $favorites = $favorite->getFavorites($id);

        $data = [
            'comic' => $comic,
            'comments' => $comments,
            'favorites' => $favorites,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    // 投稿を消去
    public function destroy(Comic $comic, String $id)
    {
        $user = auth()->user();
        // 消去されていないことを確認
        if(!$this->checkPost($id)) {
            return  response()->json([
                'status' => 'error',
                'message' => 'already has deleted'
            ]);
        }
        // 投稿の検証
        if(!$this->checkDeletePost($user->id, $id)) {
            return  response()->json(['status' => 'error']);
        }

        $comic->comicDestroy($user->id, $id);

        return response()->json(['status' => 'success']);
    }

    
    // ** 投稿の検証
    // 1. 投稿IDの投稿が消去されていないか？
    private function checkPost(String $comic_id)
    {
        $comic = Comic::where('id', $comic_id)->first();
        if($comic->deleted_at != null) {
            return false;
        }

        return true;
    }

    // ** 消去する投稿の検証
    // 1. 与えられた投稿IDがComics.idに存在するか？
    // 2. 投稿IDの投稿主がログインしているユーザーか？
    private function checkDeletePost(String $user_id, String $comic_id)
    {
        $comic = Comic::where('id', $comic_id)->first();
        if(!$comic) {
            return false;
        }

        if(!$comic->user_id == $user_id) {
            return false;
        }

        return true;
    }
}
