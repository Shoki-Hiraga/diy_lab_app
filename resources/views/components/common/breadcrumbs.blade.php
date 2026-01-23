@if (!empty($breadcrumbs))
<nav class="breadcrumbs">
    <ol>
        @foreach ($breadcrumbs as $crumb)
            <li>
                @if ($crumb['url'])
                    <a href="{{ $crumb['url'] }}">
                        {{ $crumb['label'] }}
                    </a>
                    <span class="breadcrumb-separator">/</span>
                @else
                    <span class="breadcrumb-current">
                        {{ $crumb['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
