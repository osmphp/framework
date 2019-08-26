# Documentation Guidelines #

Things to keep in mind:

* target text to [our reader](#our-reader)
* keep different [types of contents](#topic-types) separated
* fit topic organically into [main table of contents](#table-of-contents)
* [explain concepts](#explaining-concepts) near first usage or provide a link to topic explaining it

## Our Reader ##

Our main reader is **average backend developer**.

She is good in HTML and CSS, average in PHP, average in MySql, beginner in JS, SASS and WebPack and other non-backend tehnologies.

> **Note**. "She" is often used to refer both sexes, I don't know why. If this term discriminates anyone, we should find better one. If not, let's use "she" as a simpler form of "he/she".

She is mainly interested in getting things done with minimum effort. She hates:

* going too deep into things not directly helping her to deliver current task
* reading something which is not explained right away

She reads documentation of other software products too and expects ours to be somewhat similar in terms of style, formatting, navigation, vocabulary etc.

Other readers:

* **Advanced user** sometimes hacking a line or two to get some extra feature. She needs recipes for cool features which are easy to build and which are not prebuilt for other mortals.
* **Senior developer** solving hard issues, architecting custom project or contributing to the framework. She needs deep understanding of how things work under the hood.

## Topic Types ##

According to [What nobody tells you about documentation - Blog - Divio](https://www.divio.com/blog/documentation/), there are 4 different types of documentation which should not be mixed in the same topic:

* tutorials
    * is learning-oriented
    * allows the newcomer to get started
    * is a lesson
* how-to guides
    * is goal-oriented
    * shows how to solve a specific problem
    * is a series of steps
* explanation
    * is understanding-oriented
    * explains
    * provides background and context
* reference
    * is information-oriented
    * describes the machinery
    * is accurate and complete

There will also be 5th topic type: empty topics only containing table of contents of child pages.

## Table Of Contents ##

First of all, we will have a lot of topics and keeping all them in one list is not that convenient. Let's agree that 2 top levels of documentation should be shown in main table of contents:

It starts with general stuff:

    Before You Begin
        Key Features
        Preparing Your Computer
        Contribution Guide
        Upgrade Guide

Then we add a section containing all tutorials:

    Tutorials
        Flat File Documentation Website
        ...

In-depth topics grouped into major sections:

    Architecture
        Modules
        Configuration
        Translations
        ...
    Web Development
        Routes And Controllers
        ...
    JavaScript Development
        MACAW
        Controllers
        Models
        Actions
        Updating DOM
        ...
    PHP Development
        Console Commands
        Scheduled Jobs
        Queued Jobs
        ...
    Database Development
        ...
    UI Component Library
        ...
    Testing
        ...
    Other Topics
        ...

## "Preparing Your Computer" Structure ##

Child topics:

    Windows/Apache
    Linux/Nginx
    Linux/Apache
    Windows/Linux VM/Nginx

Each child topic also covers how to enable needed PHP extensions.

By the end of child topic developer should be fully prepared to conveniently work with Dubysa.

## Tutorial Structure ##

"Flat File Documentation Website" starts with tutorial description. In it, we explain that by finishing this tutorial you will have fully functioning documentation website, describe expected directory structure including `index.md` and topic sorting, describe allowed TOC or other tags.

Topic ends with child TOC. Each child topic does one step a time:

    Creating Project
    Creating `App_Docs` Module
    Adding `doc_root` Setting
    Adding `/show` Route
    ...

Maybe tutorial description should be separate child topic "Overview".

## Explaining Concepts ##

Every concept used throught the text should be briefly explained, so that our reader 100% understands the text. Link to full explanation should be there if she wants to go deeper.

**Example.**

To render a view, assign `template` property and then just convert view object to string:

    use Osm\Framework\Views\View;

    $view = View::new(['template' => 'Your_Module_Name.hello']);
    $html = (string)$view;

> **Note**. Above, we used short property assignment syntax, equivalent to creating an object and assigning property to it:
>
    $view = View::new();
    $view->template = 'Your_Module_Name.hello';

>It is a feature of all classes inherited from `Osm\Core\Object_` and views are inherit from this class. See also: [Objects](#).

Maybe we should keep a list of concepts which we assume our reader knows, such as PHP class property.

## In-Depth Topic Structure ##

It starts with explanation of concepts, or how it works.

Then how-tos follow. In how-tos, again, we shortly explain concepts from other topics and give links to in-depth topic.

At this stage, we should avoid creating reference topics. Instead we should tell where in code reader can find full list of applicable constants and public methods. Maybe later we will generate PHPDoc, publish it and provide links to it if reference topic is appropriate.

Maybe concepts and how-tos should be not in the same topic, but instead in separate child topics.

