<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    public function index()
    {
        //
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

    public function favorite(Request $request, Favorite $favorite)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'comic_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }

        $user = auth()->user();
        $comic_id = $data['comic_id'];

        $is_favorite = $favorite->isFavorite($user->id, $comic_id);
        if (!$is_favorite) {
            $favorite->storeFavorite($user->id, $comic_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Favorited',
                ]);
        } else {
            $favorite->destroyFavorite($user->id, $comic_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Unfavorited',
                ]);
        }
    }

    public function isFavorite(Request $request, Favorite $favorite)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'comic_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }

        $user = auth()->user();
        $comic_id = $data['comic_id'];

        $is_favorite = $favorite->isFavorite($user->id, $comic_id);
        
        return response()->json([
            'status' => 'success',
            'message' => (string) $is_favorite,
        ]);
    }
}
