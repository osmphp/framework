# Composer Guide #

{{ toc }}

## Introduction ##

New project is typically created from Composer package of `project` type (or *project template*):

	composer create-project osmphp/osmphp

This command copied all the package files into new project's directory and also installs all dependent Composer packages of `library` type (or just *packages*) into `vendor` directory of the project. `osmphp/osmphp` project template only depends on `osmphp/framework` package.

You may install more packages into the project:

	composer require osmphp/docs
	composer require osmphp/shop
	...

>**Note**. Currently syntax of command which create projects and adds more packages is a bit more complex, please refer to the documentation of these packages.

Composer packages are backed by Git repositories, and you can create project or add package based on any commit in Git repository. Most often you name commits to be used (in Composer world, it is called *version constraint*) by either by version tag assigned to them or by branch name pointing to them:

	// use package commit with certain version tag assigned
	composer create-project osmphp/osmphp:1.1.*
	composer require osmphp/docs:1.1.*

	// use package commit under `master` branch
	composer create-project osmphp/osmphp:dev-master
	composer require osmphp/docs:dev-master

Use version tag constraint for "read only" package use and safe package updates to newer versions.

Typically, specify exact major and and minor version number in version constraint and leave patch number to be picked for you (`1.1.*`), so that patches can be installed without supervision, just using `composer update` and major and minor versions would require some supervision.

Use branch constraint for package development.

If you don't specify any version constraint, commit with latest assigned version tag is installed.

## Version Tags ##

Version tags are assigned using [semantic versioning rules](https://semver.org/):

>Given a version number MAJOR.MINOR.PATCH, increment the:
>
>1. MAJOR version when you make incompatible API changes,
>2. MINOR version when you add functionality in a backwards-compatible manner, and
>3. PATCH version when you make backwards-compatible bug fixes.

### Major Versions ###

With some exceptions new major version (with some exceptions) of each package is released every 6 weeks along with a guide on how to upgrade it safely from previous version.

To keep your project on the latest version, update version constraint in `composer.json`, run `composer update` and do the steps listed in the upgrade guide.

Major version is increased in every package even if it doesn't have breaking changes for consistency It is recommended for third-party package developers to test their packages against new major version and release their package with the same major version (`2.0.0`, `3.0.0`, ...).

### Minor Versions ###

Minor versions may be released between major releases.

If minor version is released and you want to use it, update version constraint in `composer.json`, run `composer update` and do the steps listed in the upgrade guide.

### Patches ###

Patches are released regularly.

To apply the latest patches, run `composer update` command.

## Branches ##

All active development is done on `master` branch.

There are also support branches for patch development for each supported major and minor version: `1.1` for developing `1.1.*` patches, `2.0` for developing `2.0.*` patches and so on. Support branches are created after major or minor version release.

Current major version, previous major version and all their minor versions are supported. Later we will introduce LTS (long-term support) versions having longer (2-3 years) support period. Support branches are deleted once specific versions are no longer supported.

## Projects ##

After you create new project from project template using `composer create-project` command, it is not under Git. And that's OK. However, put project files under Git whenever you want to

* share the project with fellow developer
* put the project into production
* modify project files and keep version history

At the very least, project Git repository should have one `master` branch used for all new development. In `composer.json` of the `master` branch, require `dev-master` for packages which are actively developed in this projects and use version constraints for all the other packages.

Once project is live, also create and maintain `live` branch pointing to commit currently being in production. In `composer.json` of the `live` branch, use version constraints for all packages.

## Publishing New Package Version ##

Make sure all changes are in `master` branch, push `master` branch to the server and run:

	cd [package_directory]

	# see all tags of the package and decide which new tag should be assigned
	git tag

If major or minor version changes, update `readme.md` and run:

	git commit -am "version bump"
	git push origin master
	git branch X.Y
	git push origin X.Y

If major version changes, delete version branches of versions which are no longer supported.

Finally assign the tag:

	git tag [vX.Y.Z]
	git push --tags

## Publishing New Project Template Version ##

New version of `osmphp/osmphp` package should be released not only if project files change, but also whenever new version of `osmphp/framework` is released.

Branches and version tags in `osmphp/osmphp` package mimic ones of `osmphp/framework` package.

If it is your first release, prepare local project first:

	cd [directory_containing_all_projects]
	git clone git@bitbucket.org:osmphp/osmphp.git osmphp-project-template
	cd osmphp-project-template
	composer install

Pick version tag same as last published version tag of `osmphp/framework` package.

If major or minor version changes, update `readme.md`. Then continue in shell:

	composer update
	git commit -am "version bump"
	git push origin master
	git tag [vX.Y.Z]
	git push --tags

## Sharing Project With Other Developer ##

Push project Git repository on server. Then on the other developer's machine:

1. Run:

		cd [directory_containing_all_projects]
		git clone [repository_url] [project_directory]
		cd [project_directory]
		composer update

2. Configure Web Server to host project's `public` directory.

3. Create MySql user and database for the project.

4. Run:

		php run installer

## Installing Project In Production ##

Push `live` branch of project Git repository on server. Then on the server:

1. Run:

		cd [directory_containing_all_projects]
		git clone -b live [repository_url] [project_directory]
		cd [project_directory]
		composer update

2. Configure Web Server to host project's `public` directory.

3. Create MySql user and database for the project.

4. Run:

		php run installer
