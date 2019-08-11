# Areas #

Area is a part of application with different set of [routes](#) and/or different set of [asset files](#).

- `web` area provides Web pages for users. Typically it is divided into 2 sub-areas:
	- `frontend` area provides web content to end-user
	- `backend` is used by internal staff
- `api` area provides HTTP REST API for interacting with 3rd party services

> **Note.** By default, Webpack doesn't process area's asset files. To make area assets "visible" to Webpack, create `js/index.js` or `critical-js/index.js` asset in area resource directory of any module.

{{ child_pages }}