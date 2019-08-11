# Calculating Properties In Derived Classes #

In some cases, you may introduce `@required` property which is calculated on first access in derived classes.

	/**
	 * @property string $type @required @part
	 */
	class Shape extends Object_ {
	}
	
	class Point extends Shape {
		protected function default($property) {
	        switch ($property) {
	            case 'type': return 'point';
	        }
	        return parent::default($property);
	    }
	}
	
	class Polygon extends Shape {
		protected function default($property) {
	        switch ($property) {
	            case 'type': return 'polygon';
	        }
	        return parent::default($property);
	    }
	} 

See also:

* [Polymorphic Properties](../object-types/polymorphic-properties.html)