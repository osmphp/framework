# HTML IDs #

All HTML elements with bound JS behavior are required to render `id` attribute nd in most cases, JS behavior is bound to outer element of a view. Each view has `id_` property which is rendered in view template into HTML id attribute if needed:

    <div id="{{$view->id_}}">
        <!-- ... -->
    </div>

## Automatic HTML IDs ##

In most cases you can rely on automatically calculated `id_` which same as view `id` (if assigned) or parent view's `id_` suffixed with name of parent view's property containing this view. If for example, layout is

    View::new([
        'id' => 'root',
        `child` => View::new([...]),
        'another_child' => View::new([...]),
    ])

Then HTML IDs will be assigned as follows:

    <div id="root">
        <div id="root__child">
        </div>
        <div id="root__another_child">
        </div>
    </div>

Parent HTML ID and property name are separated with double underscore (`__`).

## Assigning Aliases ##

Parent property name is also known as **alias**. Alias can be assigned manually:

    View::new([
        'id' => 'root',
        `child` => View::new(['alias' => 'custom', ...]),
        'another_child' => View::new([...]),
    ])

    <div id="root">
        <div id="root__custom">
        </div>
        <div id="root__another_child">
        </div>
    </div>

Aliases are typically assigned when views are created directly in Blade template file, not in layer file:

    @include (Button::new(['alias' => 'close_button', ...]))

## Manual HTML IDs ##

You may assign view `id_` property directly and assigned value will be used exactly as typed:

    View::new([
        'id_' => 'my_id',
    ])

Result:

    <div id="my_id">
    </div>

## Omitting Parent HTML ID ##

`Page` view is assigned `null` HTML ID, which not only says that HTML elelemnt doesn't have any ID, but also that child view HTML IDs are not prefixed.

Example:

    View::new([
        'id' => 'root',
        'id_' => null,
        `child` => View::new([...]),
        'another_child' => View::new([...]),
    ])

Then HTML IDs will be assigned as follows:

    <div>
        <div id="child">
        </div>
        <div id="another_child">
        </div>
    </div>

## Omitting Aliases ##

If view has `content` property, its child view aliases are prefixed with additional underscore (`_`) and HTML IDs children of view assigned to `content` property are prefixed with their grandparent HTML ID. It makes much shorter HTML IDs.

Example:

    View::new([
        'id' => 'root',
        `child` => View::new([...]),
        'content' => View::new([
            'some' => View::new([...]),
            'other' => View::new([...]),
        ]),
    ])

HTML IDs will be assigned as follows:

    <div id="root">
        <div id="root___child">
        </div>
        <div id="root___content">
            <div id="root__some">
            </div>
            <div id="root__other">
            </div>
        </div>
    </div>

## Arrays Of Views ##

Automatic HTML ID assignment works for properties containing array of views too:

    View::new([
        'id' => 'root',
        `child` => View::new([...]),
        'children' => [
            'some' => View::new([...]),
            'other' => View::new([...]),
        ],
    ])

Result:

    <div id="root">
        <div id="root__child">
        </div>
        <div>
            <div id="root__children_some">
            </div>
            <div id="root__children_other">
            </div>
        </div>
    </div>

If array property is named `views`, then this name id not added to generated HTML IDs and other views are prefixed with additional underscore, similar to `content` property:

    View::new([
        'id' => 'root',
        `child` => View::new([...]),
        'views' => [
            'some' => View::new([...]),
            'other' => View::new([...]),
        ],
    ])

Result:

    <div id="root">
        <div id="root___child">
        </div>
        <div>
            <div id="root__some">
            </div>
            <div id="root__other">
            </div>
        </div>
    </div>

## More Complex View Structures ##

In some cases, child views are buried in more complex data structure than directly in view property or in view array property.

Example of such view is `PopupMenu`, its submenus are deep inside in array of menu items (which are not view objects).

Such views should have custom `$view->iterator` which would iterate through all child views.