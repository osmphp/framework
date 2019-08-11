# Adding New Project With SSL Certificate To Server Configuration #

1. With `root` user, create `/etc/nginx/sites-available/{project}`:

		server {
			listen 80;
			listen [::]:80;
		    server_name {project_domain};
		
			root /projects/{project}/public;
		
			index index.html index.php;
		
		    location / {
		        try_files $uri $uri/ /index.php?$query_string;
		    }
		
		    location = /favicon.ico { access_log off; log_not_found off; }
		    location = /robots.txt  { access_log off; log_not_found off; }
		
		    access_log off;
		    error_log  /var/log/nginx/{project}-error.log error;
		
		    sendfile off;
		
		    client_max_body_size 100m;
		
		    location ^~ /development/ {
		        expires 30d;
		    }
		    location ^~ /testing/ {
		        expires 30d;
		    }
		    location ^~ /backend/ {
		        expires 30d;
		    }
		    location ^~ /frontend/ {
		        expires 30d;
		    }
		
			location ~ \.php$ {
		        fastcgi_split_path_info ^(.+\.php)(/.+)$;
		        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
		        fastcgi_index index.php;
		        include fastcgi_params;
		        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		
		        fastcgi_intercept_errors off;
		        fastcgi_buffer_size 16k;
		        fastcgi_buffers 4 16k;
		        fastcgi_connect_timeout 300;
		        fastcgi_send_timeout 300;
		        fastcgi_read_timeout 300;
		    }
		
		    location ~ /\.ht {
		        deny all;
		    }
		
		    listen 443 ssl http2;
		    ssl_certificate     /etc/nginx/ssl/{project_domain}.crt;
		    ssl_certificate_key /etc/nginx/ssl/{project_domain}.key;
		
		    ssl_session_timeout 1d;
		    ssl_session_cache shared:SSL:50m;
		    ssl_session_tickets off;
		
		    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
		    ssl_ciphers 'ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES256-SHA:ECDHE-ECDSA-DES-CBC3-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:DES-CBC3-SHA:!DSS';
		    ssl_prefer_server_ciphers on;
		
		    ssl_stapling on;
		    ssl_stapling_verify on;
		
		    add_header Strict-Transport-Security max-age=15768000;
		}
	
2. With `root` user, run in shell:

	    ln -fs "/etc/nginx/sites-available/{project}" "/etc/nginx/sites-enabled/{project}"
	    service nginx restart
 
	
