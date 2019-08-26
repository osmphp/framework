# Upgrade Guide #

## 0.2 -> 0.3 ##

Starting from `0.3` version:

* core and `Osm_Framework_*` modules moved to new `osmphp/framework` package.
* it is recommended to specify version constaint instead of `dev-master@dev` (to run `composer update` without fear of breaking changes) 

### `composer.json` ###

Remove reference to obsolete Satis repository `https://www.manadev.com/osmphp.com/` in `repositories` section.

Add reference to `osmphp/framework` Git repository:

    "repositories": {
        "osmphp_framework": {
            "type": "vcs",
            "url": "git@bitbucket.org:osmphp/framework.git"
        }
    },

Add `osmphp/framework` package as a dependency:

    "require": {
        ...
        "osmphp/framework": "0.3.*"
    },

If you only use `Osm_Framework_*` modules, remove `osmphp/components` dependency from requires section. Otherwise change version constraint to `0.3.*`. 

### Other ###

Update `.componentignore` file. If you added lines there to ignore all components except framework - just delet these lines and instead remove `osmphp/components` from `composer.json`. If you ignore some framework component, update paths to `vendor/osmphp/framework`.

Run:

    composer update 

 
 
    

