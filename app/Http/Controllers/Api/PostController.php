<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{

    /** 
     * 
     * Create a post for Specific user
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json(['post' => $post], 201);
    }


    /**
     * Retrieve all posts with their comments and the user who created them.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::with(['comments', 'user'])->get();
        return response()->json(['posts' => $posts], 200);
    }

    /**
     * 
     * Post Display 
     * 
     */
    public function show(Post $post)
    {
        $post->load('comments', 'user');
        return response()->json(['post' => $post], 200);
    }


    /**
     * 
     * 
     * Cache the result of the top 5 posts with the most comments query for 10 minutes.
     * 
     */
    public function topPosts()
    {
        // Retrieve the top 5 posts with the most comments and cache the result for 10 minutes
        $posts = Cache::remember('top_posts', 10 * 60, function () {
            return Post::withCount('comments')
                       ->orderBy('comments_count', 'desc')
                       ->take(5)
                       ->get();
        });

        return response()->json($posts);
    }
}
