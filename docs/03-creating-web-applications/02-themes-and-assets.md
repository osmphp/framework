# Themes And Assets

Out of the box, Osm Framework support multiple *themes*. It means that you can completely change look and feel of the website by changing a single setting - the current theme name.

A theme is basically a named collection of *assets* - Blade templates, CSS styles, JavaScript files, images, and other files that render the website. Theme assets are collected and built using `gulp` and `gulp watch` commands.

More details:

{{ toc }}

### meta.abstract

Out of the box, Osm Framework support multiple *themes*. It means that you can completely change look and feel of the website by changing a single setting - the current theme name.

A theme is basically a named collection of *assets* - Blade templates, CSS styles, JavaScript files, images, and other files that render the website. Theme assets are collected and built using `gulp` and `gulp watch` commands.

## Standard Themes

Osm Framework comes with two themes: `_base` and `_front__tailwind`.

### `_base` Theme

[`_base`](https://github.com/osmphp/framework/tree/HEAD/themes/_base) theme contains assets that should be present in any theme: JavaScript class library, Blade templates defining page layout, and Gulp scripts that preprocess CSS and JS files, and copy all public theme assets to the `public/Osm_App` directory.  

### `_front__tailwind` Theme
 
[`_front__tailwind`](https://github.com/osmphp/framework/tree/HEAD/themes/_front__tailwind) theme is the default theme of the [`front` area](01-request-response-loop.md#areas). It extends the `_base` theme, and adds [Tailwind CSS](https://tailwindcss.com/) support.

## Creating Theme

You can also create your own themes. 

1. Pick a theme name using `_{area}__{theme_name}` pattern. Here, `{area}` is area name (`front` or `admin`), and `{theme_name}` is some unique identifier of your choosing. Let's assume that the name of the new theme is `_front__my`.

2. Create a theme directory `themes/_front__my` directory, and `osm_theme.json` theme configuration file inside, and specify the parent theme that your theme inherits all assets from, for example: 

        {
            "parent": "_front__tailwind"
        } 

3. Add asset files there, more on that on a moment.

4. Add the new theme to the [`gulpfile.js`](../01-getting-started/05-configuration.md#gulp):

        ...
        global.config = {
            'Osm_Tools': [],
            'Osm_Project': [],
            'Osm_App': ['_front__tailwind', '_front__my']
        }; 
        ...

5. Restart Gulp in the command line:

        gulp && gulp watch 

## Adding Assets to Existing Theme

Instead of creating your own theme, you may add files to an existing theme, for example, the `_front__tailwind` theme:
 
1. Just as with a new theme, create `themes/_front__tailwind` directory with the `osm_theme.json` file in it:

        {
        } 
 
2. Add asset files there. Actually, every new project already contains such a directory with a Blade template that renders the welcome page: 

        themes/
            _front__tailwind/
                osm_theme.json
                views/
                    welcome/
                        home.blade.php

3. Restart Gulp in the command line:

        gulp && gulp watch 

Internally, when Gulp build a theme, it checks its files in the `themes/_front__tailwind` directory of *every* Composer package, including the project directory, that contains at least one application module. 

## Theme Inheritance

A theme inherits all files from its parent theme, except `osm_theme.json`. For example, the `_base` theme introduces the `views/std-pages/layout.blade.php` file, and its child theme, `_front__tailwind`, also automatically gets this file, even if it doesn't define explicitly.

Files are inherited recursively. It means that a child themes of the `_front__tailwind` theme also have all the files of the `_base` theme, including the `views/std-pages/layout.blade.php` file.  

A child theme may override its parent theme files. For example, you can copy the `views/std-pages/layout.blade.php` file and customize it there as needed.

Finally, a child theme may introduce its own asset files. 

## Theme Structure

At the very minimum, each theme directory contains the `osm_theme.json` configuration file. However, it may contain a lot more:

    osm_theme.json
    tailwind.config.js
    css/
        {module}/
            styles.css
        ...
        theme/     
            styles.css
    fonts/
        {module}/...
        ...
        theme/...
    gulp/
        ...
    images/
        {module}/...
        ...
        theme/...
    js/
        {module}/
            scripts.js
        ...    
        theme/
            scripts.js
    views/
        {module}/...
        ...

Store assets categorized both by type (`css/`, `js/`, `views/`, ...) and [module name](../02-writing-php-code/02-modules.md#module-name). It's important, as Gulp works withs assets of modules that are a part of your application, and ignores the rest.  

For example, check the [`_front__my`](https://github.com/osmphp/osmsoftware-website/tree/HEAD/themes/_front__my) theme of the [osm.software](https://osm.software/) website.

For the rest of this document, let's imagine that we are building a `posts` module for a blogging application, and various theme assets for it.
 
### Blade Templates (`views/`)

Inside the theme directory, put Blade templates of your module into the `views/{module}/` directory. For example, Blade template directory of the `posts` module is `views/posts`.

Gulp collects all Blade template files into the `temp/{app}/{theme}/views/` directory, and in runtime, the application loads Blade templates from this directory, too.

### CSS Styles (`css/`)

Inside the theme directory, put CSS styles specific to your module into the `css/{module}/styles.css` file. For example, a CSS file for the `posts` module is `css/posts/styles.css`.

Define styles that are not specific to any module in the `css/theme/styles.css` file.

Gulp processes CSS files in two stages. First, it collects all CSS files into the `temp/{app}/{theme}/css/` directory. Then it bundles them into a single file, `public/{app}/{theme}/styles.css` using [PostCSS](https://postcss.org/). In runtime, the application includes this file on all its pages.    

In your theme, you may customize PostCSS configuration, or use another CSS processor by overriding `gulp/css.js` file, more on that [below](#gulp-scripts-gulp). 

### JavaScript Files (`js/`)

Inside the theme directory, write module-specific JavaScript in the `js/{module}` directory, and import it into the `js/{module}/scripts.js` file. For example, in the `posts` module, define a comment form controller (a JavaScript class that may be attached to certain DOM elements and alter their behavior) in the `js/posts/Controllers/CommentForm.js` file, and import it into the `js/posts/scripts.js` file:

    import './Controllers/CommentForm'; 

Write JavaScript that is not specific to any module in the `js/theme` directory, and import them into the `js/theme/scripts.js` file.

Gulp processes JS files in two stages. First, it collects all JS files into the `temp/{app}/{theme}/js/` directory. Then it bundles them together into a single file, `public/{app}/{theme}/scripts.js` using [Rollup](https://rollupjs.org/guide/en/). In runtime, the application includes this file on all its pages.    

In your theme, you may customize Rollup configuration, or use another JS bundler by overriding `gulp/js.js` file, more on that [below](#gulp-scripts-gulp). 

### Other Public Assets (`files/`, `fonts/`, `images/`)

Inside the theme directory, put other public assets specific to your module, for example, images, into the `css/{module}/styles.css` file. For example, images of the `posts` module go to `images/posts` directory.

Put module-independent images into the `images/theme` directory.

Gulp puts all the images relevant to the application into the `public/{app}/{theme}/images` directory, fonts - into the `public/{app}/{theme}/fonts` directory, and other files - into `public/{app}/{theme}/files` directory. 

Reference these files in your Blade templates and CSS. For example, if the `posts` module defines `images/posts/banner.png`, reference it in the `css/posts/styles.css` file keeping in mind the structure of the `public/` directory:

    .banner {
        background: url('images/posts/banner.png');
    }   

### Tailwind Configuration (`tailwind.config.js`)

[Tailwind CSS](https://tailwindcss.com/), a CSS framework used in the standard `_front__tailwind` theme, and custom themes that derived from it, has its own configuration file, `tailwind.config.js`.

Explore available settings in the [Tailwind CSS documentation](https://tailwindcss.com/docs/configuration).

### Gulp Scripts (`gulp/`)

Each theme contains its own Gulp scripts, located in the theme's `gulp/` directory, and, if needed, you can customize for your theme. For example, you can use SASS or LESS instead of PostCSS.

Prior to running theme-specific Gulp scripts, the `gulp` command collects theme files from all Composer packages, all its parent themes into the `temp/{app}/{theme}/` directory.

After that, the `gulp` command run a child Gulp process with the configuration specified in the `temp/{app}/{theme}/gulp/main.js` file. As of the standard `_front__tailwind` theme, theme-specific Gulp process:

1. Clears the public directory `public/{app}/{theme}`.
2. Copies fonts, images and other asset files into the public directory.
3. Bundles all module-specific CSS styles into a single file, `public/{app}/{theme}/styles.css`.
4. Bundles all module-specific JavaScript code into a single file, `public/{app}/{theme}/scripts.js`.
5. Generates new `public/{app}/{theme}/version.txt` file that is forces browsers to reload public assets, even if the assets have been saved in a browser's cache. 
