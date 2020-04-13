<?php
/* @var \Osm\Ui\Forms\Views\Tab $view */
?>
<section class="form-tab" id="{{ $view->id_ }}">
    <header class="form-tab__header">
        <h2 class="form-tab__title">{{ $view->title }}</h2>
    </header>
    <div class="form-fields" id="{{ $view->id_ }}___fields">
        @foreach ($view->views_ as $child)
            @if (!$child->empty)
                <div class="form-fields__wrap {{$child->wrap_modifier}}">
                    @include ($child)
                </div>
            @endif
        @endforeach
    </div>
    <script>new Osm_Ui_Forms.Fields('#{{ $view->id_}}___fields')</script>
</section>
