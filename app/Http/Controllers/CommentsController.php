<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    // コメントを投稿
    public function store(Request $request, Comment $comment)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'comic_id' => ['required', 'string'],
            'text' => ['required', 'string', 'max:140'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 200);
        }

        $user = auth()->user();
        $comment->commentStore($user->id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Commented'
            ]);
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

    public function destroy(Comment $comment, String $id)
    {
        $user = auth()->user();
        $comment->commentDestroy($user->id, $id);

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted',
            ]);
    }
}
