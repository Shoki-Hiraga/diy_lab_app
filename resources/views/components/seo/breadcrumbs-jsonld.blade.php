@props([
    'breadcrumbs' => [],
])

@php
if (empty($breadcrumbs)) {
    return;
}

$data = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($breadcrumbs)->values()->map(function ($crumb, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['label'],
            'item' => $crumb['url'] ?? null,
        ];
    })->filter(fn ($item) => $item['item'] !== null)->values(),
];
@endphp

<script type="application/ld+json">
@json($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
</script>
