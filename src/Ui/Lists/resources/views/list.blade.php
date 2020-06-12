<?php /* @var \Osm\Ui\Lists\Views\List_ $view */ ?>
<div class="list {{ $view->type }}">
    @foreach ($view->section_items as $section => $items)
        @if (count($items))
            <ul class="list__items -{{ $section }}">
                @foreach ($items as $item)
                    <?php
                        /* @var object $item */
                        $view->item = $item;
                    ?>

                    <li class="list__item -data">
                        @include ($view->item_template, ['view' => $view])
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
    @if (!$view->refreshing)
        <script type="text/template" class="list__item-template">
            <li class="list__item -placeholder">
                @if ($view->placeholder_template)
                    @include ($view->placeholder_template, ['view' => $view])
                @endif
            </li>
        </script>
    @endif
</div>