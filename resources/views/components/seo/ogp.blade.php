@php
$title = trim($__env->yieldContent('ogp_title')) ?: config('app.name');
$description = trim($__env->yieldContent('ogp_description')) ?: 'DIYラボ｜DIY初心者から上級者まで役立つ情報を発信';
$image = trim($__env->yieldContent('ogp_image')) ?: asset('images/ogp/default.png');
$url = trim($__env->yieldContent('ogp_url')) ?: url()->current();
@endphp

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:url" content="{{ $url }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
