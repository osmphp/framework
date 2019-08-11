# UI Components #

This section lists all standard UI components and provides quick example of using it.

{{ page_toc }}

TODO:

* forms
* aria
* data tables
* app bars

## Icons ##

    <i class="icon -cart"></i>

See also: [Icon Options](../icons.md)

## Modal Overlay ##

You can disable all mouse actions on the page with the following JS code:

    $('.overlay').addClass('-modal');

To enable mouse actions back, add:

    $('.overlay').removeClass('-modal');

## Buttons ##

Add to the beginning of blade template file:

    <?php
    // ...
    use Manadev\Ui\Buttons\Views\Button;
    ?>

For standard HTML buttons, define it as follow and then handle its clicks with JavaScript:

    @component(Button::new(['alias' => 'flat_button']))
        {{ m_("Flat Button") }}
    @endcomponent

Link as button:

    @component(Button::new(['alias' => 'flat_link', 'url' => m_url('GET /tests/')]))
        {{ m_("Flat Link") }}
    @endcomponent

See also: [Button Options](../button.md)

## Page Dialogs ##

Page dialogs are small pages taking whole screen property, typical example is login form.

Add to the beginning of blade template file:

    <?php
    // ...
    use Manadev\Ui\Dialogs\Views\PageDialog;
    ?>

Then use it in template:

    @component(PageDialog::new(['alias' => 'main', 'width' => 500]))
        Put content here
    @endcomponent

See also: [Page Dialog Options](../page-dialogs.md)

## Inputs ##

Add to the beginning of blade template file:

    <?php
    // ...
    use Manadev\Ui\Inputs\Views\Input;
    ?>

Then use it in template:

    @include(Input::new(['name' => 'username', 'modifier' => '-filled', 'title' => m_("User name")]))
    @include(Input::new(['name' => 'password', 'modifier' => '-filled -password', 'title' => m_("Password")]))

See also: [Input Options](../inputs.md)
