<?php

namespace Osm\Tools\WebServer\Commands;

use Osm\Framework\Console\Attributes\Option;
use Osm\Framework\Console\Command;
use Osm\Tools\WebServer\Traits\DomainArgumentTrait;

/**
 * @property string $project_path
 * @property string $php_version
 * @property string $php_fastcgi_pass
 * @property string $listen
 * @property bool $prevent_network_access #[Option]
 *
 * @uses Option
 */
class ConfigNginx extends Command
{
    use DomainArgumentTrait;

    public string $name = 'config:nginx';

    public function run(): void
    {
        file_put_contents('nginx_virtual_host.conf', <<<EOT
server {
{$this->listen}
    server_name {$this->actual_domain};

    root {$this->project_path}/public/{$this->actual_app};

    index index.html index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log /var/log/nginx/{$this->actual_domain}-access.log combined;
    error_log  /var/log/nginx/{$this->actual_domain}-error.log error;

    sendfile off;

    client_max_body_size 100m;

    location ^~ /_ {
        expires 30d;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)\$;
        fastcgi_pass {$this->php_fastcgi_pass};
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;

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
EOT);
    }

    protected function get_project_path(): string {
        return getcwd();
    }

    protected function get_php_version(): string {
        return PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    }

    protected function get_php_fastcgi_pass(): string {
        return "unix:/var/run/php/php{$this->php_version}-fpm.sock";
    }

    protected function get_listen(): string {
        return $this->prevent_network_access
            ? "    listen 127.0.0.1:80;\n    listen [::1]:80;"
            : "    listen 80;\n    listen [::]:80;";
    }
}