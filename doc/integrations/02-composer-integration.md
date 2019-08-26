# Composer Integration #

Integration with [Composer](https://getcomposer.org/). 

[composer create-project](../../php-development/osmphp-console-commands.html#composer-create-project)


Osm processes new `composer.json` section `scripts` which is used to perform specific actions together with standard Composer commands.

Here is example of this section:

    "scripts": {
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('temp/.env', '.env');\"",
            "@php -r \"file_exists('.env.testing') || copy('temp/.env.testing', '.env.testing');\""
        ],
        "post-update-cmd": [
            "@php fresh",
            "@php run composer-hooks post-update"
        ],
        "post-create-project-cmd": [
            "@php fresh",
            "@php run composer-hooks post-create-project"
        ]
    }

In this example additional scripts are described:    
- In `post-root-package-install` after root package is installed, two additional files are copied.
- After `composer-update` execution cache is refreshed and `post-update` command is called. Osm modules can add custom actions in this step. For instance, database module adds scripts to update database structure. JavaScript and CSS module validate and combine all .js and .csss files. 
- After `composer-create-project` cache is refreshed and `post-create-project` is executed. Purpose is the same as in command above - to have possibility for specific module to run additional actions after project creation.

{{ child_pages }}