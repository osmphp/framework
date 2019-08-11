# Initializing Properties In Constructor #

You can assign initial property values while creating an object:

    $point = Point::new(['x' => 3.0, 'y' => 5.0]);

If property is assigned a value in constructor, its initial value and default value logic are ignored.
