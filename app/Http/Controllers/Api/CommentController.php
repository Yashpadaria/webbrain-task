<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Jobs\SendCommentNotification;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
         // Validate the incoming request
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Create the comment
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        // Inside store method
        SendCommentNotification::dispatch($comment);

        return response()->json($comment);
    }
}
