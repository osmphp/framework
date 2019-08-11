# Cache #

Cache is a component for storing object data for future requests in order to retrieve the data faster than recalculate from the very beginning.  

For instance, quite often Dubysa need to read configuration files from all enabled modules 
and consolidate all to one file. It is definitely faster to retrieve saved consolidated file from temporary location than to perform reading, validation and consolidation again.

The module provide methods to create and terminate, as well as validate or invalidate cache.


{{ child_pages }}