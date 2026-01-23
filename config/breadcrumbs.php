<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Difficulty;

return [

    // =========================
    // Public
    // =========================

    'public.posts.index' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
    ],

    'users.posts.show' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        // 🔥 将来：カテゴリをここに差し込める
        [
            'label' => fn(Post $post) => $post->title,
            'route' => fn(Post $post) => route('users.posts.show', $post),
        ],
    ],

    'categories.index' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => 'カテゴリ一覧'],
    ],

    'categories.show' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => 'カテゴリ一覧', 'route' => 'categories.index'],
        [
            'label' => fn(Category $category) => $category->name,
            'route' => fn(Category $category) => route('categories.show', $category),
        ],
    ],

    'tags.index' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => 'タグ一覧'],
    ],

    'tags.show' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => 'タグ一覧', 'route' => 'tags.index'],
        [
            'label' => fn(Tag $tag) => $tag->name,
            'route' => fn(Tag $tag) => route('tags.show', $tag),
        ],
    ],

    'difficulties.index' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => '難易度一覧'],
    ],

    'difficulties.show' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => '難易度一覧', 'route' => 'difficulties.index'],
        [
            // 🔑 型ヒントに依存しないで、値だけ使う
            'label' => fn($difficulty) => '★' . $difficulty->id,
            'route' => fn($difficulty) => route('difficulties.show', $difficulty),
        ],
    ],

    'creators.index' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => '投稿者一覧'],
    ],

    'creators.show' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => '投稿者一覧', 'route' => 'creators.index'],
        [
            'label' => fn(User $user) => $user->name,
            'route' => fn(User $user) => route('creators.show', $user),
        ],
    ],

    'search.index' => [
        ['label' => 'トップ', 'route' => 'public.posts.index'],
        ['label' => '検索結果'],
    ],

    // =========================
    // Users（ログイン後）
    // =========================

    'users.posts.index' => [
        ['label' => 'マイページ'],
    ],

    'users.posts.create' => [
        ['label' => 'マイページ', 'route' => 'users.posts.index'],
        ['label' => '新規投稿'],
    ],

    'users.posts.edit' => [
        ['label' => 'マイページ', 'route' => 'users.posts.index'],
        [
            'label' => fn(Post $post) => $post->title,
            'route' => fn(Post $post) => route('users.posts.show', $post),
        ],
        ['label' => '編集'],
    ],

    'users.likes' => [
        ['label' => 'マイページ', 'route' => 'users.posts.index'],
        ['label' => 'いいね一覧'],
    ],

    'users.bookmarks' => [
        ['label' => 'マイページ', 'route' => 'users.posts.index'],
        ['label' => 'ブックマーク'],
    ],

    'users.profile.show' => [
        ['label' => 'マイページ', 'route' => 'users.posts.index'],
        ['label' => 'プロフィール'],
    ],

];
