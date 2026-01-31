{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- 固定ページ --}}
    <url><loc>{{ route('public.posts.index') }}</loc><priority>1.0</priority></url>
    <url><loc>{{ route('categories.index') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('tags.index') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('creators.index') }}</loc><priority>0.8</priority></url>

    {{-- 投稿詳細 --}}
    @foreach ($posts as $post)
        <url>
            <loc>{{ route('users.posts.show', $post) }}</loc>
            <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <priority>0.9</priority>
        </url>
    @endforeach

    {{-- カテゴリ詳細 --}}
    @foreach ($categories as $category)
        <url>
            <loc>{{ route('categories.show', $category) }}</loc>
            <priority>0.7</priority>
        </url>
    @endforeach

    {{-- タグ詳細 --}}
    @foreach ($tags as $tag)
        <url>
            <loc>{{ route('tags.show', $tag) }}</loc>
            <priority>0.5</priority>
        </url>
    @endforeach

    {{-- 作成者プロフィール --}}
    @foreach ($creators as $creator)
        <url>
            <loc>{{ route('creators.show', $creator) }}</loc>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>