@extends('layouts.app')

@section('content')
<div class="post-form-container">
    <h2>新しい投稿を作成</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>・{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="title">タイトル</label>
        <input type="text" name="title" id="title" value="{{ old('title') }}" required>

        <label for="category">カテゴリ</label>
        <select name="category_id" id="category" required>
            <option value="">選択してください</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label for="body">本文</label>
        <textarea name="body" id="body" rows="6" required>{{ old('body') }}</textarea>

        <label for="image">画像 (任意)</label>
        <input type="file" name="image" id="image" accept="image/*">

        <button type="submit">投稿する</button>
    </form>
</div>
@endsection
