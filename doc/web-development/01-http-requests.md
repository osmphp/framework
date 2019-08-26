# HTTP Requests

Client browser sends HTTP requests, while server sends HTTP responses.

HTTP request contain **request method**, usually `GET` or `POST`. 
Any URL entered in browser address bar initiate `GET` HTTP requests to 
retrieve data from this URL.

Server sends HTML text back to browser to show the page.

**Request URL** is the address of the resource in web. 

In general URL consists of 4 parts `[base URL][/path][?query][#fragment]`

For example in URL `http://127.0.0.1/osmphp-docs/?param1=value`

 - base URL `http://127.0.0.1/osmphp-docs` is a local address of our project is hosted
 - path `/` means homepage of the project
 - query `?param1=value` define additional information to serve the request
 - this URL does not contain `#fragment` 

{{ child_pages }}