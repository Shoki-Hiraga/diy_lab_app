@props([
    'posts',
])

@php
$data = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'DIY LAB 投稿一覧',
    'description' => 'DIY LAB の投稿一覧ページです。',
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $posts->values()->map(function ($post, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('users.posts.show', $post),
                'name' => $post->title,
            ];
        }),
    ],
];
@endphp

<script type="application/ld+json">
@json($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
</script>
