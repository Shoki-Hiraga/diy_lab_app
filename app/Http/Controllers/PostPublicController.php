<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostPublicController extends Controller
{
    public function show(Post $post)
    {
        $post = Post::published()
            ->whereKey($post->id)
            ->with(['categories', 'tools', 'contents', 'user.profile'])
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

}
