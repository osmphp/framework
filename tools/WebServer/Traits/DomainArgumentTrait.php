<?php

namespace Osm\Tools\WebServer\Traits;

use Dotenv\Parser\Parser;
use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\Console\Attributes\Argument;
use Osm\Framework\Console\Attributes\Option;

/**
 * @property ?string $domain #[Argument]
 * @property string $actual_domain
 * @property string $app #[Option]
 * @property string $actual_app
 *
 * @uses Argument, Option
 */
trait DomainArgumentTrait
{
    protected function get_actual_domain(): string {
        if ($this->domain) {
            return $this->domain;
        }

        $filename = ".env.{$this->actual_app}";

        if (is_file($filename)) {
            $parser = new Parser();

            foreach ($parser->parse(file_get_contents($filename)) as $entry) {
                if ($entry->getName() === 'NAME') {
                    return str_replace('_', '',
                            $entry->getValue()->get()->getChars()) . '.local';
                }
            }
        }


        throw new NotSupported("Either specify the domain name " .
            "as a command argument, or specify NAME env variable " .
            "in `{$filename}`.");
    }

    protected function get_actual_app(): string {
        return $this->app ?? 'Osm_App';
    }
}