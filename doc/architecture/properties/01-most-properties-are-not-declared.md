# Most Properties Are Not Declared #

Instead, `@property` tag is added to class comment:

    /**
     * @property float $x
     * @property float $y
     */
    class Point extends Object_
    {
    }

Initially, instantiated object of `Point` initially doesn't have `x` and `y` properties at all. However, properties are created when first accessed:

    $point = Point::new();
    // $point->x and $point->y don't exist

    $x = $point->x;
    // $point->x is created and assigned null value on first access

    $point->y = 5.0;
    // $point->y is created and assigned 5.0 value on first access

All undeclared properties are public.

