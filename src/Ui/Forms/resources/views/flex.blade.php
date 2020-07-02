<?php /* @var \Osm\Ui\Forms\Views\Flex $view */ ?>
<div id="{{ $view->id_ }}" class="form-flex
    {{ $view->on_color_ }} {{ $view->color_ }}"
>
    @foreach ($view->items_ as $child)
        @if (!$child->empty)
            <div class="form-flex__item {{$child->wrap_modifier_}} wrap">
                @include ($child)
            </div>
        @endif
    @endforeach
</div>
