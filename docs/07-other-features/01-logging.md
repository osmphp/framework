# Logging

Osm Framework uses [Monolog](https://github.com/Seldaek/monolog/blob/main/doc/01-usage.md) library for logging. Use standard logs defined in the `$osm_app->logs` object, or add your own. Control logging in the `settings.{app_name}.php` and `.env.{app_name}` files.  

Details:

{{ toc }}

### meta.abstract

Osm Framework uses *Monolog* library for logging. Use standard loggers defined in the `$osm_app->logs` object, or add your own. Control logging in the `settings.{app_name}.php` and `.env.{app_name}` files.  

## Usage
 
Logs are defined as properties of the `$osm_app->logs` object. For example, `$osm_app->logs->db` records all queries issued to the database, while `$osm_app->logs->default` is for generic purposes, in case there is no more specific log.   

In order to use a log in your class, define the log as a dependency property:

    /**
     * @property Logger $log
     */
    class Foo extends Object_ {
        protected function get_log(): Logger {
            global $osm_app; /* @var App $osm_app */
            
            return $osm_app->logs->default;        
        }
        ...
    }

Then create log entries using its methods, for example, `info()`:

    /**
     * @property Logger $log
     */
    class Foo extends Object_ {
        protected function get_log(): Logger {
            global $osm_app; /* @var App $osm_app */
            
            return $osm_app->logs->default;        
        }
        
        public function bar(): void {
            $this->log->info('Hello, world!');        
        }
    }

## Standard Logs

`$osm_app->logs` object has predefined standard logs.

### `default`

Use this log for generic purposes. By default, it's recorded to the `temp/{app_name}/default.log` file.

### `http`

This log accumulates unhandled PHP exceptions that occur while processing HTTP requests. By default, it's stored in the `temp/{app_name}/http.log` file.

This log is only used in production, if `PRODUCTION` environment variable is set to `true`. Otherwise, exceptions are shown to the user.

### `db`

This log records all queries issued to the database. By default, it's stored in
the `temp/{app_name}/db.log` file.

This log must be enabled in [application settings](#settings).

### `elastic`

This log records all requests sent to the ElasticSearch, and its responses. By default, it's stored in the `temp/{app_name}/elastic.log` file.

This log must be enabled in [application settings](#settings).

## Enabling Logs   

`default` and `http` logs are always enabled.

Enable `db` and `elastic` logs in the `settings.{app_name}.php` file, or, even better, let them be managed by the environment variables:

    return (object)[
        ...
        /* @see \Osm\Framework\Logs\Hints\LogSettings */
        'logs' => (object)[
            'elastic' => (bool)($_ENV['LOG_ELASTIC'] ?? false),
            'db' => (bool)($_ENV['LOG_DB'] ?? false),
        ],
    ];
    
In the `.env.{app_name}` file, set mentioned variable to `true`:

    ...
    LOG_ELASTIC=true
    LOG_DB=true

## Adding Your Own Logs

Add a dynamic trait to the [`Logs`](https://github.com/osmphp/framework/blob/HEAD/src/Logs/Logs.php) class, add a property of `Logger` class, and configure it in its getter:

    /**
     * @property Logger $my
     */
    trait LogsTrait
    {
        protected function get_my(): Logger {
            global $osm_app; /* @var App $osm_app */
    
            $logger = new Logger('my');
            $logger->pushHandler(new RotatingFileHandler(
                "{$osm_app->paths->temp}/logs/my.log"));
    
            return $logger;
        }
    }

Then, add log entries using new `$osm_app->logs->my` property.
 
## Customizing Standard Logs

Add a dynamic trait to the [`Logs`](https://github.com/osmphp/framework/blob/HEAD/src/Logs/Logs.php) class, and add code around the log getter:

    trait LogsTrait
    {
        protected function around_get_my(callable $proceed): Logger {
            global $osm_app; /* @var App $osm_app */
    
            // configure as specified in the original method
            $logger = $proceed();

            // in addition, send log entries as emails
            options = [...];

            // Create the Swift_mail transport
            $transport = \Swift_SmtpTransport::newInstance(
                    $options['host'], $options['port'], 'ssl'
            )
                ->setUsername($options['username'])
                ->setPassword($options['password']);

            // Create the Mailer using your created Transport
            $mailer = Swift_Mailer::newInstance($transport);

            // Create a message
            $message = Swift_Message::newInstance($options['subject'])
                ->setFrom($options['from'])
                ->setTo($options['to'])
                ->setBody('', 'text/html');

            $handler = new SwiftMailerHandler($mailer, $message, 
                $options['log_level'], $options['bubble']);

            $handler->setFormatter(new HtmlFormatter());
            $logger->pushHandler($handler);
    
            return $logger;
        }
    }
