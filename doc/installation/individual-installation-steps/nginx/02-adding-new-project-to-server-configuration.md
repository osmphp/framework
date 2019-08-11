# Adding New Project To Server Configuration #

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
		        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
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
		}
	
2. With `root` user, run in shell:

	    ln -fs "/etc/nginx/sites-available/{project}" "/etc/nginx/sites-enabled/{project}"
	    service nginx restart
 
	
