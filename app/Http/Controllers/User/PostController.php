<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\Category;
use App\Models\Tool;
use App\Models\Tag;
use App\Services\ImageCompressor;

class PostController extends Controller
{
    /**
     * 新規投稿画面
     */
    public function create()
    {
        return view('users.posts.create', [
            'categories' => Category::all(),
            'tools'      => Tool::all(),
            'user'       => Auth::user()->load('profile'),
        ]);
    }

    /**
     * 編集画面
     */
    public function edit(Post $post)
    {
        $this->authorizePost($post);

        return view('users.posts.edit', [
            'post'       => $post->load(['categories', 'tools', 'contents', 'tags']),
            'categories' => Category::all(),
            'tools'      => Tool::all(),
            'user'       => Auth::user()->load('profile'),
        ]);
    }

    /**
     * 投稿保存
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|max:255',
            'difficulty_id' => 'required|exists:difficulties,id',
            'category_id'   => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'tools.*'       => 'nullable|exists:tools,id',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'comments.*'    => 'nullable|string|max:1000',
            'status'        => 'required|in:draft,published',
            'tags'          => 'nullable|string|max:255',
        ]);

        $post = Post::create([
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'difficulty_id' => $request->difficulty_id,
            'status'        => $request->status,
        ]);

        $post->categories()->sync($request->category_id);
        $post->tools()->sync($request->tools ?? []);

        // タグ同期
        $tagIds = $this->syncTags($request->tags);
        $post->tags()->sync($tagIds);

        // === 画像＋コメント保存（40KB圧縮） ===
        foreach ($request->file('images', []) as $index => $image) {
            if (!$image) continue;

            $path = ImageCompressor::compressAndStore(
                $image,
                'posts'
            );

            PostContent::create([
                'post_id'    => $post->id,
                'image_path' => $path,
                'comment'    => $request->comments[$index] ?? null,
                'order'      => $index,
            ]);
        }

        return redirect()
            ->route('users.posts.index')
            ->with('success', '投稿を保存しました');
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
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'comments.*'    => 'nullable|string|max:1000',
            'existing_contents'           => 'nullable|array',
            'existing_contents.*.comment' => 'nullable|string|max:1000',
            'existing_contents.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'existing_contents.*.delete'  => 'nullable|boolean',
            'tags'                        => 'nullable|string|max:255',
        ]);

        $post->update([
            'title'         => $request->title,
            'difficulty_id' => $request->difficulty_id,
            'status'        => $request->status,
        ]);

        $post->categories()->sync($request->category_id);
        $post->tools()->sync($request->tools ?? []);

        // タグ同期
        $tagIds = $this->syncTags($request->tags);
        $post->tags()->sync($tagIds);

        // === 既存画像処理（40KB圧縮） ===
        if ($request->filled('existing_contents')) {
            foreach ($request->existing_contents as $id => $data) {
                $content = $post->contents()->find($id);
                if (!$content) continue;

                if (!empty($data['delete'])) {
                    Storage::disk('public_fileassets')->delete($content->image_path);
                    $content->delete();
                    continue;
                }

                if (isset($data['comment'])) {
                    $content->comment = $data['comment'];
                }

                if ($request->hasFile("existing_contents.$id.image")) {
                    Storage::disk('public_fileassets')->delete($content->image_path);

                    $content->image_path = ImageCompressor::compressAndStore(
                        $request->file("existing_contents.$id.image"),
                        'posts'
                    );
                }

                $content->save();
            }
        }

        // === 新規画像追加（40KB圧縮） ===
        if ($request->hasFile('images')) {
            $maxOrder = $post->contents()->max('order') ?? 0;

            foreach ($request->file('images') as $i => $image) {
                $path = ImageCompressor::compressAndStore(
                    $image,
                    'posts'
                );

                PostContent::create([
                    'post_id'    => $post->id,
                    'image_path' => $path,
                    'comment'    => $request->comments[$i] ?? null,
                    'order'      => $maxOrder + $i + 1,
                ]);
            }
        }

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

        foreach ($post->contents as $content) {
            Storage::disk('public_fileassets')->delete($content->image_path);
        }

        $post->contents()->delete();
        $post->categories()->detach();
        $post->tools()->detach();
        $post->tags()->detach();
        $post->delete();

        return redirect()
            ->route('users.posts.index')
            ->with('success', '投稿を削除しました');
    }

    /**
     * タグ同期
     */
    private function syncTags(?string $tagString): array
    {
        if (!$tagString) return [];

        return collect(preg_split('/[\s,]+/', $tagString))
            ->map(fn ($tag) => mb_strtolower(ltrim($tag, '#')))
            ->filter()
            ->unique()
            ->map(fn ($name) => Tag::firstOrCreate(['name' => $name])->id)
            ->values()
            ->toArray();
    }

    /**
     * 所有者チェック
     */
    private function authorizePost(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
