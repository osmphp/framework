<?php /* @var \Osm\Ui\Filters\Views\Filters $view */ ?>
<ul class="filters">
    @if (!$view->empty_filters)
        <h2>{{ osm_t("Filter by") }}</h2>
        @foreach ($view->items_ as $filter)
            @if (!$filter->empty)
                <li class="filters__filter">
                    @include($filter)
                </li>
            @endif
        @endforeach
    @endif
</ul>