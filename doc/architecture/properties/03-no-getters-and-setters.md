# No Getters And Setters #

Getter and setter methods shown below are **NOT USED**:

    class Polygon extends Object_
    {
        /**
         * @var Point[]
         */
        protected $points = [];

        /**
         * Getter method
         *
         * @return Point[]
         */
        public function getPoints() {
            return $this->points;
        }

        /**
         * Setter method
         *
         * @param Point[] $points
         */
        public function setPoints($points) {
            $this->points = $points;
        }
    }

It is a design choice.

Pros:

* makes data structures easily extensible by 3rd party modules.
* makes "lazy" properties possible. Internally, "lazy" properties use [magic `__get()` PHP method](http://php.net/manual/en/language.oop5.overloading.php#object.get) which, in turn, only works for properties not defined yet
* more convenient while debugging
* less verbose
* works faster

Cons:

* user code can put object into incorrect state by assigning property incorrect value. To prevent that, treat all properties as readonly and don't assign them a value after object is created.
* you can't put any logic in getter - as there is no getter. Most of use cases are solved via lazy properties, see below. However, if some logic should run every time property is accessed, consider creating a method instead, put that logic into it.

