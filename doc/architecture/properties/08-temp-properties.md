# `@temp` Properties #

In some classes, you may notice the following pattern: several parameters are passed from method to method:
 
    class Mesh extends Object_
    {
        public function draw(Surface $surface, Brush $brush, Pencil $pencil) {
			$this->drawEdges($surface, $brush, $pencil);
			$this->drawTriangles($surface, $brush, $pencil);
			$this->applyLighting($surface, $brush, $pencil);
			$this->applyShaders($surface, $brush, $pencil);
		}

		protected function drawEdges(Surface $surface, Brush $brush, Pencil $pencil) {
			...
		}

		protected function drawTriangles(Surface $surface, Brush $brush, Pencil $pencil) {
			...
		}

		protected function applyLighting(Surface $surface, Brush $brush, Pencil $pencil) {
			...
		}

		protected function applyShaders(Surface $surface, Brush $brush, Pencil $pencil) {
			...
		}
    }

Such classes are fragile to change: adding more parameters to one method requires adding the same parameters to other methods. If same methods are overridden in derived classes, parameter list must be updated in derived classes as well. 

You may offload variables into `@temp` properties to simplify passing parameters across methods:

    /**
     * @property Surface $surface @temp
     * @property Brush $brush @temp
     * @property Pencil $pencil @temp
     */
    class Mesh extends Object_
    {
        public function draw(Surface $surface, Brush $brush, Pencil $pencil) {
			$this->surface = $surface;
			$this->brush = $brush;
			$this->pencil = $pencil;
			
			try {
				$this->drawEdges();
				$this->drawTriangles();
				$this->applyLighting();
				$this->applyShaders();
			}
			finally {
				$this->surface = null;
				$this->brush = null;
				$this->pencil = null;
			}
		}

		protected function drawEdges() {
			...
		}

		protected function drawTriangles() {
			...
		}

		protected function applyLighting() {
			...
		}

		protected function applyShaders() {
			...
		}
    }

`@temp` property values only makes sense while the method which initializes and releases them is executed. You can even omit clearing `@temp` properties in finally block if that doesn't create unwanted side effects:

    public function draw(Surface $surface, Brush $brush, Pencil $pencil) {
		$this->surface = $surface;
		$this->brush = $brush;
		$this->pencil = $pencil;
		
		$this->drawEdges();
		$this->drawTriangles();
		$this->applyLighting();
		$this->applyShaders();
	}
 

