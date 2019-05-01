<?php
/* @var \Manadev\Framework\Views\View $view */
use Manadev\Ui\Buttons\Views\Button;
?>
<p>
    @include(Button::new(['alias' => 'button', 'title' => m_("Normal Button")]))
    @include(Button::new(['alias' => 'link', 'title' => m_("Normal Link"), 'url' => m_url('GET /tests/')]))
    @include(Button::new(['alias' => 'disabled', 'title' => m_("Normal Disabled"), 'modifier' => '-disabled']))
</p>
<p>
    @include(Button::new(['alias' => 'outlined_button', 'title' => m_("Outlined Button"),
        'modifier' => '-outlined']))
    @include(Button::new(['alias' => 'outlined_link', 'title' => m_("Outlined Link"),
        'modifier' => '-outlined', 'url' => m_url('GET /tests/')]))
    @include(Button::new(['alias' => 'outlined_disabled', 'title' => m_("Outlined Disabled"),
        'modifier' => '-outlined -disabled']))
</p>
<p>
    @include(Button::new(['alias' => 'filled_button', 'title' => m_("Filled Button"),
        'modifier' => '-filled']))
    @include(Button::new(['alias' => 'filled_link', 'title' => m_("Filled Link"),
        'modifier' => '-filled', 'url' => m_url('GET /tests/')]))
    @include(Button::new(['alias' => 'filled_disabled', 'title' => m_("Filled Disabled"),
        'modifier' => '-filled -disabled']))
</p>
<hr>
<p>
    @include(Button::new(['alias' => 'primary_dark', 'title' => '-dark', 'modifier' => '-dark']))
    @include(Button::new(['alias' => 'outlined_primary_dark', 'title' => '-outlined -dark',
        'modifier' => '-outlined -dark']))
    @include(Button::new(['alias' => 'filled_primary_dark', 'title' => '-filled -dark',
        'modifier' => '-filled -dark']))
</p>
<p>
    @include(Button::new(['alias' => 'primary', 'title' => '-primary', 'modifier' => '-primary']))
    @include(Button::new(['alias' => 'outlined_primary', 'title' => '-outlined -primary',
        'modifier' => '-outlined -primary']))
    @include(Button::new(['alias' => 'filled_primary', 'title' => '-filled -primary',
        'modifier' => '-filled -primary']))
</p>
<p>
    @include(Button::new(['alias' => 'primary_light', 'title' => '-light', 'modifier' => '-light']))
    @include(Button::new(['alias' => 'outlined_primary_light', 'title' => '-outlined -light',
        'modifier' => '-outlined -light']))
    @include(Button::new(['alias' => 'filled_primary_light', 'title' => '-filled -light',
        'modifier' => '-filled -light']))
</p>
<p>
    @include(Button::new(['alias' => 'secondary_dark', 'title' => '-secondary -dark',
        'modifier' => '-secondary -dark']))
    @include(Button::new(['alias' => 'outlined_secondary_dark', 'title' => '-outlined -secondary -dark',
        'modifier' => '-outlined -secondary -dark']))
    @include(Button::new(['alias' => 'filled_secondary_dark', 'title' => '-filled -secondary -dark',
        'modifier' => '-filled -secondary -dark']))
</p>
<p>
    @include(Button::new(['alias' => 'secondary', 'title' => '-secondary', 'modifier' => '-secondary']))
    @include(Button::new(['alias' => 'outlined_secondary', 'title' => '-outlined -secondary',
        'modifier' => '-outlined -secondary']))
    @include(Button::new(['alias' => 'filled_secondary', 'title' => '-filled -secondary',
        'modifier' => '-filled -secondary']))
</p>
<p>
    @include(Button::new(['alias' => 'secondary_light', 'title' => '-secondary -light',
        'modifier' => '-secondary -light']))
    @include(Button::new(['alias' => 'outlined_secondary_light', 'title' => '-outlined -secondary -light',
        'modifier' => '-outlined -secondary -light']))
    @include(Button::new(['alias' => 'filled_secondary_light', 'title' => '-filled -secondary -light',
        'modifier' => '-filled -secondary -light']))
</p>
<hr>
<footer>
    <a href="{{ m_url('GET /tests/') }}">{{ m_("Back To Test List") }}</a>
</footer>