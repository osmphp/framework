# Installation #

Dubysa is installed in 2 simple steps:

1. Create new project directory from template:

		cd {home_dir}
		composer create-project -n dubysa/dubysa "--repository={\"type\":\"vcs\",\"url\":\"git@bitbucket.org:dubysa/dubysa.git\"}" {project}

2. Add the project to Web server configuration, so that Web site's root URL is served from `{project_dir}/public` directory.

## More Installation Options ##

You may want to configure more, depending on the computer configuration and tools used:

* locally or on the server
	* dedicated virtual machine (e.g. [Linode](https://www.linode.com/))
	* shared hosting (e.g. [HostGator](https://www.hostgator.com/))
* operating system: Windows, Linux (e.g. Ubuntu) or Mac
* Web server: Nginx or Apache
* IDE (e.g. PHPStorm)
* optional Vagrant virtual machine (e.g. with VirtualBox provider) with files syncing via
	* shared network directory
	* SFTP, automated by PHPStorm
* optional database support (Mysql)
* optional HTTPS support using
	* paid server SSL certificate (e.g. [SSLS](https://www.ssls.com/))
	* free server SSL certificate (e.g. [LetEncrypt](https://letsencrypt.org/))
	* self-signed SSL certificate for local development

We have prepared several step by step guides for the most common server configurations:

{{ child_pages depth="1" }}

If you don't find your exact server configuration, please consider using the most similar one and contributing     
step by step guide for your server configuration to us.

