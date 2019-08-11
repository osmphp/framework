# Windows + Vagrant + Nginx + PHPStorm + SFTP Sync + Self-Signed SSL #

This is step by step guide for installing Dubysa on local Windows computer using:

* Vagrant virtual machine serving PHP via Nginx Web server
* PHPStorm for development, debugging and syncing updated files to virtual machine
* locally created SSL certificate authority for putting local projects under HTTPS

Contents:

{{ toc }}

## Installing and Configuring Prerequisites ##

Prepare your local computer and the virtual machine before the first Dubysa installation:

1. **TODO**

## Creating The Project ##

1. [Locally: create new project from template](individual-installation-steps/projects/creating-new-project-from-template.html)
    1. [(Alternatively) Locally: clone existing project](individual-installation-steps/projects/cloning-existing-project.html)
2. [Locally: open project in PHPStorm](individual-installation-steps/phpstorm/opening-project-hosted-on-vagrant-and-using-https.html)
3. [Locally: exclude generated files from PHPStorm index](individual-installation-steps/phpstorm/excluding-generated-files-from-index.html)
4. [Locally: deploy project directory to the virtual machine via SFTP](individual-installation-steps/phpstorm/deploying-project-files-to-vagrant-using-sftp.html)
5. [On virtual machine: install Node modules used by the project](individual-installation-steps/projects/installing-node-modules-used-by-the-project.html)
6. [On virtual machine: generate assets](individual-installation-steps/projects/generating-assets.html)
7. [(Optional) On virtual machine: create the database](individual-installation-steps/projects/creating-the-database.html)
8. [On virtual machine: create SSL certificate using local authority](individual-installation-steps/linux/creating-ssl-certificate-using-local-authority.html)
9. [On virtual machine: add the project to Web server configuration](individual-installation-steps/nginx/adding-new-project-with-ssl-certificate-to-server-configuration.html)
10. [Locally: resolve project domain to virtual machine's IP address in `hosts` file](individual-installation-steps/windows/resolving-unregistered-project-domain-to-your-server.html)
11. [Locally: check that project is responding](individual-installation-steps/projects/checking-that-project-is-responding-to-https-requests.html)

## Preparing For Project Development ##

1. **TODO**
