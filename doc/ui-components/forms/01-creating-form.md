# How-To: Creating Form #

This guide provides code snippets for creating newsletter subscription form. Use these code snippets to create your own forms and replace [placeholders in square brackets](#) as well as `NewsletterSubscriptionForm` and `newsletter_subscription_form` identifiers.

[Run `npm run watch` while changing source files](#). 

Keep in mind that, this guide first shows usages of classes/templates to be created an after that shows how to create them.

Steps:

{{ toc }}


## Render Form ##

[Render form inside your template](#):

    <?php
    use [module_namespace]\Views\NewsletterSubscriptionForm; 
    ?>
    
    ...
    
    @include(NewsletterSubscriptionForm::new())

Alternatively, [assign form as a property to parent view in layer file and render that property in parent view template](#). 
 
## Create Form Class ##

Create [mentioned form class](#) (link to view class definition) in `[module_path]/Views/NewsletterSubscriptionForm.php` file and [override `content_template`, `route` and `view_model` properties](#):

    <?php
    
    namespace [module_namespace]\Views;
    
    use Manadev\Framework\Views\View;
    use Manadev\Ui\Forms\Views\Form;
    
    class NewsletterSubscriptionForm extends Form
    {
        // specify template to render fields and buttons inside the form 
        public $content_template = '[module_name].newsletter_subscription_form';
    
        // specify route to handle submitted form contents
        public $route = 'POST /newsletters/subscribe';

        // attach JS view-model to form to handle form data submission
        public $view_model = '[module_name].NewsletterSubscriptionForm';
    } 

## Create Template To Render Fields And Buttons ##

Create [mentioned template](#) (link to view template definition) in `[module_path]/[area_resource_path]/views/newsletter_subscription_form.blade.php` file. 

Use `form.form__header` and `form.form__footer` CSS classes to mark form header and footer:

    <?php
    /* @var Manadev\Framework\Views\View $view */ 
    
    use Manadev\Ui\Inputs\Views\Input;
    use Manadev\Ui\Buttons\Views\Button;
    ?>
    <header class="form__header">
        {{ m_("Subscribe To Our Newsletter") }}
    </header>
    
    @include(Input::new(['name' => 'email', 'title' => m_("Email"),
        'modifier' => '-filled', 'required' => true]))
    
    <footer class="form__footer">
        @component(Button::new(['alias' => 'subscribe', 'modifier' => '-filled'])){{ m_("Subscribe") }}@endcomponent    
    </footer>
    
> **Note**. Restart `npm run watch` if `[module_path]/[area_resource_path]/views` directory didn't exist before this step.

## Register JS View-Model Class ##

[Register mentioned JS view-model class](#) in `[module_path]/[area_resource_path]/critical-js/index.js`:

    import merge from 'Manadev_Framework_Js/merge';
    import SignUpForm from './NewsletterSubscriptionForm';
    
    merge(window, {
        Your_Module_Name: { NewsletterSubscriptionForm }
    });

> **Note**. Restart `npm run watch` if `[module_path]/[area_resource_path]/critical-js/index.js` file didn't exist before this step.

## Create JS View-Model Class ##

[Create mentioned JS view-model class](#) in `[module_path]/[area_resource_path]/critical-js/NewsletterSubscriptionForm.js`:

    import Form from 'Manadev_Ui_Forms/Form';
    
    export default class NewsletterSubscriptionForm extends Form {
    };

## Attach JS Controller Class ##

[Attach JS controller class](#) to all HTML elements marked with mentioned view-model in `[module_path]/[area_resource_path]/js/index.js`:

    import macaw from "Manadev_Framework_Js/vars/macaw";
    import NewsletterSubscriptionForm from "./NewsletterSubscriptionForm";
    
    macaw.controller(Your_Module_Name.NewsletterSubscriptionForm, NewsletterSubscriptionForm); 

> **Note**. Restart `npm run watch` if `[module_path]/[area_resource_path]/js/index.js` file didn't exist before this step.

## Create JS Controller Class ##
 
[Create mentioned JS controller class](#) in `[module_path]/[area_resource_path]/js/NewsletterSubscriptionForm.js`:
    
    import Form from 'Manadev_Ui_Forms/Form';
    import snackBars from 'Manadev_Ui_SnackBars/vars/snackBars';
    import m_ from "Manadev_Framework_Js/m_";
    
    export default class NewsletterSubscriptionForm extends Form {
        get events() {
            return Object.assign({}, super.events, {
                '&__subscribe': 'onSubmit'
            });
        }
    
        onSuccess(payload) {
            snackBars.showMessage(m_("Thank you for subscribing."));
        }
    };

## Add Translations ##

Add mentioned [translatable strings](#) to `[module_path]/config/translations/en_US.php` file:

    <?php
    
    return [
        ...
        "Subscribe To Our Newsletter" => "Subscribe To Our Newsletter",
        "Email" => "Email",
        "Subscribe" => "Subscribe",
    ];

