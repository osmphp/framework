# Conventions #

{{ toc }}

## Placeholders ##

Throughout the documentation we often use `{placeholder}` syntax to denote parts of shell commands or source code which should not be pasted into the shell or the editor as is, but instead should be replaced with computer-specific, project-specific or task-specific value.

When using sample code containing placeholders, consider copying it to temporary editor window using `Replace All` feature of the editor to replace placeholders with actual values and then copying the resulting text into the shell or the editor.  

In the list below you will find all used placeholders in alphabetical order with the description on what they mean and what they should be replaced with along with most often used value, if applicable.

* `{db_name}` - name of MySql database storing project's data. 
	* Usually same as `{project}`
	* If `{project}` is longer than 10 characters or if it contains non-alphanumeric characters, shorten `{project}` to 10 characters and remove non-alphanumeric characters.  
* `{db_password}` - project's MySql user password. 
	* Consider using tools like [passwordsgenerator.net](https://passwordsgenerator.net/?length=16&symbols=0&numbers=1&lowercase=1&uppercase=1&similar=1&ambiguous=0&client=1&autoselect=0) for generating strong passwords.
* `{db_user}` - project's MySql user name dedicated for accessing project's MySql database.
	* usually, same as `{db_name}` 
* `{home_dir}` - directory containing all your projects. Depending on where this placeholder is used, the directory may be on your computer or on the server. 
	* In Windows, consider keeping projects in `D:\_projects`. 
	* In Linux, consider keeping projects in `/projects`.
* `{module_namespace}` - PHP namespace of the module currently being developed. 
    * equals `{module}` with `_` replaced by `\`
    * for example: namespace of `Osm_Framework_Js` module is `Osm\Framework\Js` 
* `{package}` - Name of your Composer package currently being developed.
    * example: `osmphp/docs`
* `{project}` - name of the project you are currently working on. 
	* This name is used for project's directory name.
	* This name is usually part of project's Web domain name.
* `{project_dir}` - absolute path of the directory where project is installed.
	* short notation for `{home_dir}/{project}`
* `{project_domain}` - Web domain which your application responds to
	* usually equals `{project}.vm` when hosted on Vagrant virtual machine
* `{project_repo_url}` - URL pointing to Git server repository project is stored in
* `{server_ip}` - IP address of the server actually handling requests to your application
	* If it is your local computer, use `127.0.0.1`
	* if it is your Vagrant virtual machine, use `192.168.10.10`
	* Otherwise, find IP address of your server in your hosting provider's control panel.
	
## `npm run watch` ##

The documentation assumes that while developing the project you always have the following command:

	npm run watch

This command updates generated asset files once source asset files are modified and, most importantly, it clears the cache after any source file change. 