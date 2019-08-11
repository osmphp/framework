# `@required` Properties #

Properties marked with `@required` attribute will throw an error if accessed not initialized or being equal to `null`:

    /**
     * @property float $x @required
     * @property float $y @required
     */
    class Point extends Object_
    {
    }

    $point = Point::new();
    $x = $point->x;
    // error is thrown

Required properties are initialized:

* [in `default()` method of the class](lazy-properties.html)
* [in `default()` method of derived classes](calculating-properties-in-derived-classes.html)
* [in constructor](initializing-properties-in-constructor.html)