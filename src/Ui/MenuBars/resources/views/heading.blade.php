<?php
/* @var \Manadev\Ui\MenuBars\Views\Heading $view */

use Manadev\Ui\MenuBars\Views\MenuBar;

?>
<div class="heading">
    <h1 class="heading__title">{{ $view->title }}</h1>
    @if (!empty($view->items))
        @include(MenuBar::new(['alias' => 'menu', 'items' => $view->items, 'modifier' => 'heading__menu']))
    @endif
</div>
