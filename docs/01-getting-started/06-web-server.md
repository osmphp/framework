# Web Server

Run your application under a Web server - a program that browsers actually communicate with. For development, consider using native PHP Web server. On a production server, use Nginx, although you may use it locally, too. 

Step-by-step guides:

{{ toc }}

### meta.abstract

Run your application under a Web server - a program that browsers actually communicate with. For development, consider using native PHP Web server. On a production server, use Nginx, although you may use it locally, too.

## Native PHP Web Server

The easiest way to try out the application is to use the Web server that is bundled with PHP.

Start the native PHP Web Server in the project directory:
    
    # start the Web application on the `8000` port
    php -S 0.0.0.0:8000 -t public/Osm_App public/Osm_App/router.php
    
While the Web server is running, open the application home page in a browser: <http://127.0.0.1:8000/>.

## Nginx

In order to run your application under Nginx Web server, create and enable a *virtual host*. In simple terms, it's a setup that instructs Nginx to execute your application whenever it receives an HTTP request for the specified domain name. The whole procedure is described below.

Before you begin, purchase the domain name (let's say, it's `www.example.com`), and make sure it's configured to resolve at your production server (let's say `123.456.78.90`). 

As domain configuration changes take up to 24 hours to be applied. While waiting, configure your local machine so, that whenever *you* open a page from the `www.example.com`, the request is sent to your production server. 

In order to achieve that, add the following line to the `hosts` file on your local machine using `root` privileges:

    123.456.78.90 www.example.com
    
In Linux, it's located in the `/etc` directory. In Windows, the `hosts` file is located in the `C:\Windows\System32\drivers\etc` directory.  

### Creating Virtual Host

With `root` privileges, create a `/etc/nginx/sites-available/{domain}` configuration file, replace `{domain}` with your actual domain name `www.example.com`, and `{project_path}` - with the absolute path to the project directory:

    server {
        listen 80;
        listen [::]:80;
        server_name {domain};
        
        root {project_path}/public/Osm_App;
        
        index index.html index.php;
        
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
        
        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
        
        access_log /var/log/nginx/{domain}-access.log combined;
        error_log  /var/log/nginx/{domain}-error.log error;
        
        sendfile off;
        
        client_max_body_size 100m;
        
        location ^~ /_ {
            expires 30d;
        }
        
        location ~ \.php$ {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
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

### Enabling Virtual Host

Run the following commands (again, replace `{domain}` with your actual domain name `www.example.com`):

    sudo ln -s /etc/nginx/sites-available/{domain} /etc/nginx/sites-enabled/{domain}
    sudo service nginx restart
    
### Enabling HTTPS

Most websites run under HTTPS - a secure protocol that encrypts all the traffic between a browser and your Web server. 

The most often used solution is [Let's Encrypt](https://letsencrypt.org/), consult its documentation for a step-by-step guide.

## Apache

### Configuring Default Virtual Host

On your development machine, configure the default virtual host, either for a single project or multiple projects.

#### Single Project

After installing Apache Web server and enabling its `rewrite` module, configure Apache to serve files from the project directory in `/etc/apache2/sites-available/000-default.conf` file:

    <VirtualHost *:80>
        ...
        DocumentRoot {project_path}/public/Osm_App

        <Directory "{project_path}/public/Osm_App">
                AllowOverride all
                Require all granted
        </Directory>
        ...
    </VirtualHost>

After restarting Apache, open <http://127.0.0.1/> URL in your browser.  

#### Multiple Projects

Alternatively, configure Apache to serve files from a directory that contains all your projects in `/etc/apache2/sites-available/000-default.conf` file:  

    <VirtualHost *:80>
        ...
        DocumentRoot {parent_project_dir}

        <Directory "{parent_project_dir}">
                AllowOverride all
                Require all granted
        </Directory>
        ...
    </VirtualHost>

After restarting Apache, open any `{project}` using <http://127.0.0.1/{project}/public/Osm_App/> URL in your browser.