<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\Category;
use App\Models\Tool;

class PostController extends Controller
{
    /**
     * 新規投稿画面
     */
    public function create()
    {
        return view('users.posts.create', [
            'categories' => Category::all(),
            'tools' => Tool::all(),
            'user' => Auth::user()->load('profile'),
        ]);
    }

    /**
     * 編集画面
     */
    public function edit(Post $post)
    {
        $this->authorizePost($post);

        return view('users.posts.edit', [
            'post' => $post->load(['categories', 'tools', 'contents']),
            'categories' => Category::all(),
            'tools' => Tool::all(),
            'user' => Auth::user()->load('profile'),
        ]);
    }

    /**
     * 投稿保存
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|max:255',
            'difficulty_id' => 'required|integer|min:1|max:5',
            'category_id'   => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'tools.*'       => 'nullable|exists:tools,id',
            'images.*'      => 'nullable|image|max:4096',
            'comments.*'    => 'nullable|string|max:1000',
        ]);

        $post = Post::create([
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'difficulty_id' => $request->difficulty_id,
            'draft'         => $request->has('draft'),
        ]);

        // カテゴリ
        $post->categories()->sync($request->category_id);

        // ツール
        $post->tools()->sync($request->tools ?? []);

        // 画像とコメント
        foreach ($request->file('images', []) as $index => $image) {
            $path = $image ? $image->store('posts', 'public_assets') : null;

            PostContent::create([
                'post_id'    => $post->id,
                'image_path'=> $path,
                'comment'   => $request->comments[$index] ?? null,
                'order'     => $index,
            ]);
        }

        return redirect()
            ->route('users.posts.index')
            ->with('success', '投稿が完了しました！');
    }

    /**
    * 更新処理
    */
    public function update(Request $request, Post $post)
    {
        $this->authorizePost($post);

        $request->validate([
            'title'         => 'required|max:255',
            'difficulty_id' => 'required|integer|min:1|max:5',
            'category_id'   => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'tools.*'       => 'nullable|exists:tools,id',
            'images.*'      => 'nullable|image|max:4096',
            'comments.*'    => 'nullable|string|max:1000',
        ]);

        // 投稿本体を更新
        $post->update([
            'title'         => $request->title,
            'difficulty_id' => $request->difficulty_id,
            'draft'         => $request->has('draft'),
        ]);

        // カテゴリ・ツール更新
        $post->categories()->sync($request->category_id);
        $post->tools()->sync($request->tools ?? []);

        return redirect()
            ->route('users.posts.index')
            ->with('success', '投稿を更新しました！');
    }

    /**
    * 投稿削除
    */
    public function destroy(Post $post)
    {
        $this->authorizePost($post);

        $post->delete();

        return redirect()
            ->route('users.posts.index')
            ->with('success', '投稿を削除しました');
    }

    /**
     * 投稿の所有者チェック
     */
    private function authorizePost(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
