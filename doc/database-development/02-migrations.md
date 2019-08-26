# Migrations #

To create and upgrade database Osm processes scripts to prepare database schema and fill it with initial data.

PHP scripts for migration should be in module `migration/schema` directory and have `up` and `down` functions.

- `up` commands are executed during installing, 
- `down` part is used to remove (undo) changes done during installation.
 
If this script was run once, it will not be executed next time. This means that migration scripts should be incremental 
and contain commands for data modification which was not installed yet. In Osm file names 
useful to have prefixes like `01-`, `02`, etc.


{{ child_pages }}