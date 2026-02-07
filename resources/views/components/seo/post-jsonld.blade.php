@props([
    'post',
])

@php
$mainImage = optional($post->contents->first())->image_path
    ? asset('fileassets/' . $post->contents->first()->image_path)
    : asset('images/ogp/default.png');

$data = [
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => Str::limit(
        optional($post->contents->first())->comment ?? '',
        120
    ),
    'image' => [$mainImage],
    'datePublished' => $post->created_at->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Person',
        'name' => $post->user->username,
        'url' => route('creators.show', $post->user),
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'DIYラボ',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/ogp/logo.png'),
        ],
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url()->current(),
    ],
];
@endphp

<script type="application/ld+json">
@json($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
</script>
