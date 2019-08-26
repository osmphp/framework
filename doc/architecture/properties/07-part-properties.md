# `@part` Properties #

In PHP, objects can be converted to string (serialized) and created back from string (unserialized). 

Serialization is recursive. It means that if object contains another object, contained object is also converted to string during serialization and unpacked during unserialization.

Most often serialization is used in caching. Initially object is created using constructor and serialized into cache storage. On subsequent HTTP requests object is just unserialized from cache storage. 

Objects of `Osm\Core\Object_` class only serialize properties marked with `@part` attribute. It means that without `@part` attribute, after serialization and unserialization property value is lost:

    /**
     * @property float $x @required @part
     * @property float $y @required @part
     */
    class Point extends Object_
    {
    }

    class Polygon extends Object_
    {
        /**
         * @var Point[] @part
         */
        public $points = [];
    }

    $polygon = Polygon::new([
        'points' => [
            Point::new(['x' => 0.0, 'y' => 0.0]),
            Point::new(['x' => 5.0, 'y' => 2.0]),
            Point::new(['x' => 3.0, 'y' => 4.0]),
            Point::new(['x' => 1.0, 'y' => 1.0]),
        ],
    ]);
    $serialized = serialize($polygon);
    $polygon2 = unserialize($serialized);

In the example above, `$polygon2` is exact copy of `polygon`. However, if you don't mark `Polygon::$points` property as `@part`, `$polygon2` would contain empty array of points. If you don't mark `Point::$x` as `@part`, every `Point` object in `$polygon2->points` array would miss `$x` property.

It is often better not to serialize property and allow it to be missing in unserialized object. Property should not be serialized (and marked as `@part`) if property value:

* is a reference to some object which is **not part of** this object tree
* is a reference to parent object in this object tree
* should always be equal to initial value or to calculated default value
