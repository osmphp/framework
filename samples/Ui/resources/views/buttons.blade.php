<?php
/* @var \Osm\Framework\Views\View $view */
use Osm\Ui\Buttons\Views\Button;
?>
<p>
    @include(Button::new([
        'alias' => 'button',
        'title' => 'Button',
    ]))
    @include(Button::new([
        'alias' => 'link',
        'title' => 'Link',
        'url' => osm_url('GET /tests/'),
    ]))
    @include(Button::new([
        'alias' => 'disabled',
        'title' => 'Disabled',
        'disabled' => true,
    ]))
    @include(Button::new([
        'alias' => 'dangerous_button',
        'title' => 'Button',
        'outlined' => true,
        'color' => 'danger',
    ]))
    @include(Button::new([
        'alias' => 'dangerous_link',
        'title' => 'Link',
        'url' => osm_url('GET /tests/'),
        'outlined' => true,
        'color' => 'danger',
    ]))
    @include(Button::new([
        'alias' => 'dangerous_disabled',
        'title' => 'Disabled',
        'outlined' => true,
        'color' => 'danger',
        'disabled' => true,
    ]))
    @include(Button::new([
        'alias' => 'main_button',
        'title' => 'Button',
        'on_color' => 'primary',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'main_link',
        'title' => 'Link',
        'url' => osm_url('GET /tests/'),
        'on_color' => 'primary',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'main_disabled',
        'title' => 'Disabled',
        'on_color' => 'primary',
        'color' => 'neutral',
        'disabled' => true,
    ]))
</p>
<hr>
<p>
    @include(Button::new([
        'alias' => 'primary_dark',
        'title' => 'primary dark',
        'color' => 'primary dark',
    ]))
    @include(Button::new([
        'alias' => 'main_primary_dark',
        'title' => 'primary dark',
        'on_color' => 'primary dark',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'primary',
        'title' => 'primary',
        'color' => 'primary',
    ]))
    @include(Button::new([
        'alias' => 'main_primary',
        'title' => 'primary',
        'on_color' => 'primary',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'primary_light',
        'title' => 'primary light',
        'color' => 'primary light',
    ]))
    @include(Button::new([
        'alias' => 'main_primary_light',
        'title' => 'primary light',
        'on_color' => 'primary light',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'secondary_dark',
        'title' => 'secondary dark',
        'color' => 'secondary dark',
    ]))
    @include(Button::new([
        'alias' => 'main_secondary_dark',
        'title' => 'secondary dark',
        'on_color' => 'secondary dark',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'secondary',
        'title' => 'secondary',
        'color' => 'secondary',
    ]))
    @include(Button::new([
        'alias' => 'main_secondary',
        'title' => 'secondary',
        'on_color' => 'secondary',
        'color' => 'neutral',
    ]))
    @include(Button::new([
        'alias' => 'secondary_light',
        'title' => 'secondary light',
        'color' => 'secondary light',
    ]))
    @include(Button::new([
        'alias' => 'main_secondary_light',
        'title' => 'secondary light',
        'on_color' => 'secondary light',
        'color' => 'neutral',
    ]))
</p>
<hr>
<footer>
    <a href="{{ osm_url('GET /tests/') }}">{{ osm_t("Back To Test List") }}</a>
</footer>
