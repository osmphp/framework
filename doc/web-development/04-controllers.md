# Controllers

Controller is a class for processing browser requests.

Controllers are stored in module `Controllers` directory.

Different areas can process different routes. 

If there is small number of routes for given area, it is enough to have one controller class per area, 
for example `Frontend` or `Web` classes in `Controllers` directory.

Otherwise controller classes should be stored in area subdirectory like `Product` and `Category` 
controllers in `Controllers\Frontend` directory