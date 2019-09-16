<?php

namespace Osm\Framework\Queues;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property int $id @part
 * @property float $elapsed @part
 * @property object $registered_at @part
 * @property object $processed_at @part
 * @property string $key @part
 * @property LaravelJob $laravel_job @required
 * @property string $class_ @required
 * @property bool $singleton
 */
class Job extends Object_
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const FINISHED = 'finished';
    const FAILED = 'failed';

    /**
     * @required @part
     *
     * @var string
     */
    public $queue = 'default';
    /**
     * @required @part
     *
     * @var string
     */
    public $status = self::PENDING;
    /**
     * @required @part
     *
     * @var string
     */
    public $error = '';
    /**
     * @required @part
     *
     * @var string
     */
    public $log = '';
    /**
     * @required @part
     *
     * @var string
     */
    public $stack_trace = '';

    protected function default($property) {
        switch ($property) {
            case 'class_': return $this->getClass();
        }

        return parent::default($property);
    }

    protected function getClass() {
        for ($class = get_class($this); $class; $class = get_parent_class($class))
        {
            if (!starts_with($class, 'Generated\\')) {
                return $class;
            }
        }

        throw new NotImplemented("Unexpected job class '" .
            get_class($this) . "'");
    }

    public function handle() {
    }

    protected function write($message) {
        $this->laravel_job->log .= $message;
    }

    protected function writeln($message) {
        $this->write("{$message}\n");
    }
}