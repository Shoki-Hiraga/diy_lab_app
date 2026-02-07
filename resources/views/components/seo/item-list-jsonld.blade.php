@props([
    'title',
    'description' => null,
    'items',
    'routeName',
])

@php
$data = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $title,
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $items->values()->map(function ($item, $index) use ($routeName) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route($routeName, $item),
                'name' => $item->name,
            ];
        }),
    ],
];

if ($description) {
    $data['description'] = $description;
}
@endphp

<script type="application/ld+json">
@json($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
</script>
