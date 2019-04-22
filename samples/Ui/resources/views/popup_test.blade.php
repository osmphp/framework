<?php
/* @var \Manadev\Framework\Views\Views\Container $view */
?>
<div class="">
    <div class="__options">
        <select id="horizontal-overlap">
            <option value="0">Don't overlap horizontally</option>
            <option selected value="1">Overlap horizontally</option>
        </select>
        <br>
        <select id="vertical-overlap">
            <option value="0">Don't overlap vertically</option>
            <option selected value="1">Overlap vertically</option>
        </select>
        <br>
        <select id="horizontal-direction">
            <option selected value="0">Rightwards</option>
            <option value="1">Leftwards</option>
        </select>
        <br>
        <select id="vertical-direction">
            <option selected value="0">Downwards</option>
            <option value="1">Upwards</option>
        </select>
    </div>
    <div class="__viewport">
        <div class="__content">
            @foreach ($view->views as $child)
                @include ($child)
            @endforeach
        </div>
    </div>
</div>
