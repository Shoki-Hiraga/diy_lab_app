@props([
    'title',
    'items',
    'routeName',
    'countField' => null,
    'labelCallback' => null,
])

<section class="type-wrapper">
    <header>
        <h2>{{ $title }}</h2>
    </header>

    <ul class="type-list">
        @foreach ($items as $item)
            <li class="type-item">
                <a href="{{ route($routeName, $item) }}">
                    <span class="type-name">
                        @if ($labelCallback)
                            {{ $labelCallback($item) }}
                        @else
                            {{ $item->name }}
                        @endif
                    </span>

                    @if($countField)
                        <span class="type-count">
                            {{ $item->{$countField} }}
                        </span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</section>
