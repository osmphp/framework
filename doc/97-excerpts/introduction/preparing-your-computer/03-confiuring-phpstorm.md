## Open Project In PHPStorm

To set up the project in PHPStorm on Windows (if you are not on Windows, please check [Configuring PHPStorm](#)):

- in PHP select menu *File -> New Project from Existing Files*
- select folder `c:\_projects\hello` where project is installed
- configure web server for PHPStorm (where to send HTTP requests):
	- in *Specify the local server* step select *Add new local server*, 
	- name it `localhost` 
	- enter text `http://127.0.0.1/` in  `Web server URL`
	- *Web path for project root* = `hello`

After project is opened in PHPStorm check if `app/src` is marked as source root 
and every new class will have correct namespace then.