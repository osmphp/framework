<?php
/* @var \Osm\Ui\Breadcrumbs\Views\Breadcrumbs $view */
?>
@if (count($view->items_))
    <nav class="breadcrumbs">
        @foreach ($view->items_ as $item)
            @if (!$item->url)
                <span>{{ $item->title }}</span>
            @elseif (((string)$item->url === (string)$view->page_url))
                <span class="breadcrumbs__current">{{ $item->title }}</span>
            @else
                <a href="{{ $item->url }}">{{ $item->title }}</a>
            @endif
            @if (!$loop->last) &gt; @endif
        @endforeach
    </nav>
@endif
