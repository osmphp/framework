# Architecture #

This section describes the most basic concepts and internal mechanics every Dubysa application relies on. Understanding these concepts is essential to efficient application development.

Fortunately, there are only 3 basic topics to learn:

* how the application works, step by step: where the execution starts, how it dives deeper, what main classes are typically involved and how the result is generated;
* how large application is divided into smaller pieces - modules: what module can do, how it gets involved into application internals, typical structure of the module, how modules are packaged and distributed;
* how Dubysa applications are different from applications based on other PHP frameworks: dynamic traits, optional dependency injection, lazy properties.

Contents:

{{ child_pages depth="1" }}

--- 

* why Dubysa
	* I wanted to have a system where I can add property or method to some standard class or even customize how existing method of standard class works - all in a safe way. Motivation:
		* it allows freedom for any 3rd party developer to customize any part of the standard system in a way which developer of standard system couldn't even imagine
			* performance not affected
			* readability is slightly worse. When you read standard code, you should take into account that some 3rd party code could customize or even completely replace how it works. This issue is negligible with proper tooling.
			* more fragile. Class API is much wider. 
				* 3rd party is responsible to keep up with changes in standard code in timely manner.
				* the less you use this freedom, the less maintenance you have. There are other, safer ways to extend standard system
				* unit testing is extremely important. As things change, but tests pass, we can still be sure everything works as expected, given enough test coverage.  
				* loose coupling
	* I wanted to simplify dependency injection
		* for free we got lazily calculated properties
* tell this chapter to smart developer who wants loosely coupled, fun to write, maintainable code 
* what basics should I know to understand all the rest
	* 2 main use-cases: processing HTTP request and processing console command, describe them step by step
	* extensible: code base is not monolithic, app is combined from > 100 modules. To add to to the app, add more modules
		* directory structure of the app; of the module
		* what module can add to the system:
			* via configuration
			* via traits
			* via hint classes
	* what is different compared to other frameworks
		* dynamic traits
		* dependency injection
		* lazy properties


---

	class A {
		public function execute() {
			$this->doExecute();
		}

		protected function doExecute($force) {
		}
	}

public API, protected API

---

	class Db {
		/**
		 * @var Table[]
		 */
		public $tables = [];
	}

	class Db {
		
	}


	$db->create('orders', ...);

	$db['orders']->insert([...]);

	$tableManager->create();

	class Db {
		/**
		 * @var Table[]
		 */
		public $tables = [];
	}

	class Table {
		public function __construct(Db $db, Z $z) {
			$this->db = $db;
			$this->z = $z;
		}

		/**
		 * Db
		 */
		public $db;
	}

	class MySqlTable extends Table {
		public $mySqlSchema;

		public function __construct(Db $db, MySqlSchema $mySqlSchema, X $x, Y $y, $z) {
			parent::__construct($db, $z);
			$this->mySqlSchema = $mySqlSchema;
		}
	}

	class Table extends Object_ {
		public $db;

		protected function default() {
			// ...
		}
	}

	new Table();

---

{{ child_pages depth="1" }}