# Adding Certificate To The Project #

1. With `root` user, run

		certbot --nginx
	
	Then just follow instructions:
	
	1. When asked which domain to certificate, enter number near `{project_domain}` and press `Enter`
	2. When asked whether to redirect HTTP traffic to HTTS, enter `2` to enable this redirect and press `Enter`.

2. Enter `https://{project_domain}/` in browser and check if it opens the site.