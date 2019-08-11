## Referencing Tech Stack In Documentation ##

Tech stack (or prerequisites) is software needed to develop and run applications:

* Windows + Apache
* Mac + Apache
* Linux + Apache
* Linux + Nginx
* Vagrant, Linux + Nginx
* Production: Linux + Nginx (no Node)

There are several operations developer can do with tech stack:

* install
* host project directory
* create database
* restart Web server

How-to guides for all these operations will be provided for each platform in `Tools -> Tech Stack` section.

However, we need one "default stack".

For professional, better stack is Vagrant (as it is multiversion PHP, identical to production environment, same among all team members).

Mn thinks that for amateur, stack is not important, installing it is hard anyway, more important is easy to follow installation instruction.

For Vagrant, we would use `manadev/servers` project. Documenting it and starting using it team-wide is significant effort. This effort would distract us from our current projects for weeks or even months.

So no, for now we won't use Vagrant as default stack. We will return to this question after:

1. First documentation version is ready and shipped.
2. `manadev/servers` project is mature enough and is used not only for local development, but for actual server management as well.

Still, intro should not be half done. So, **Windows + Apache (Bitnami) is our default stack**.

It should go like this:

* some parts of documentation topics will be stack-specific, for instance, section on adding a project to Web server. In such parts:
    * we will write text for Windows + Apache (Bitnami), and use default paths `c:\_projects`, `c:\Program Files\Bitnami`, etc.
    * there will be a note: This part is written for users of "Windows/Apache" tech stack. If you use different stack, read [adding a project to Web server](#link to Tools -> Tech Stack -> Adding A Project To Web Server) instruction for your stack.
* other stack-agnostic parts may still refer to some stack-specific information like directory of all projects
    * it will read as: Install the application in [directory of all projects](#):

            cd c:\_projects
            composer create-project ...

* `Tools -> Tech Stack` structure:

        Overview
            Windows/Apache
            Vagrant/Nginx
            ...
        Installing Tech Stack
            Windows/Apache
            Vagrant/Nginx
            ...f
        Adding A Project To Web Server
            Windows/Apache
            Vagrant/Nginx
            ...

    * initially, these directories will only contain only Windows/Apache. Other tech stcks will be documented later.
    * Overview describes tech stack, default directories to keep in mind, other stack-specific things.

