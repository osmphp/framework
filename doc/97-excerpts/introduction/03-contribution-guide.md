# Contribution Guide #

Contents:

{{ toc }}

## CSS ... ##

### Variables ###

![description](test.png)

![](test.png)

Define global SASS variables in `[module_resource_path]/css/_variables.scss`. A new variable should be created only if either of these conditions is true (inspired by [https://sass-guidelin.es/#variables](https://sass-guidelin.es/#variables)):

* the value is repeated at least twice and all occurrences of the value are tied to the variable (i.e. not by coincidence);
* the value is expected to be customized in child theme or in settings.

Name global variables using `[property]--[element]--[name]`, where `property` is required and `element` or `name` is required, for example:

    $color--primary: #000;
    $z-index--button--raised: 2;

Document global variables. For instance, [standard global variables are documented here](../reference/css/).

## Documentation ##

Documentation practices are inspired by [https://www.divio.com/blog/documentation/](https://www.divio.com/blog/documentation/)

Documentation is organized into *books*. Each book targets specific skillset and role. For instance, "User's Guide" targets backend users, "Developer's Guide" targets module developers and so on. 

Each book consists of *topics*. Each topic is focused on interrelated concepts and tasks. For instance, "HTTP Requests and Responses" provides concentrated knowledge about handling HTTP requests and sending HTTP responses.

Each topic consists of the following *parts*:

* Tutorials
    * is learning-oriented
    * allows the newcomer to get started
    * is a lesson
* How-To Guides
    * is goal-oriented
    * shows how to solve a specific problem
    * is a series of steps
* Explanation
    * is understanding-oriented
    * explains
    * provides background and context
* Reference   
    * is information-oriented
    * describes the machinery
    * is accurate and complete

Each part is a collection of *articles*. If part is empty, it is omitted.

Each article is considered complete unless its title ends with "...".

