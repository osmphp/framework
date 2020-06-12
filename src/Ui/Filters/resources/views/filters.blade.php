<?php /* @var \Osm\Ui\Filters\Views\Filters $view */ ?>
<ul class="filters">
    @foreach ($view->items_ as $filter)
        @if (!$filter->empty)
            <li class="filters__filter">
                @include($filter)
            </li>
        @endif
    @endforeach
</ul>