# Properties Calculated On First Access (aka Lazy Properties) #

You can calculate property value first time it is accessed by overriding `default()` method:

    /**
     * @property float $top
     * @property float $left
     * @property float $bottom
     * @property float $right
     */
    class Screen extends Object_ {
        protected function default($property) {
            switch ($property) {
                case 'left': return 0;
                case 'top': return 0;
                case 'right': return /* read width from configuration file */;
                case 'bottom': return /* read height from configuration file */;
            }
            return parent::default($property);
        }
    }

Such properties are often called "lazy" as they do nothing until they are deliberately asked to. While it is debatable if it is good trait of a person, it is certainly good trait for a property :)   
