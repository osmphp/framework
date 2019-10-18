<?php

namespace Osm\Core\Packages;

use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $path @required @part
 * @property ComponentPool[] $component_pools @part @required
 * @property string[] $namespaces @part
 * @property bool $project @part
 * @property string $src @required @part
 * @property string $samples @required @part
 * @property string $tests @required @part
 * @property string $src_path @required @part
 * @property string $sample_path @required @part
 * @property string $test_path @required @part
 */
class Package extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'src': return $this->project ? 'app/src' : 'src';
            case 'samples': return $this->project ? 'app/samples' : 'samples';
            case 'tests': return $this->project ? 'app/tests' : 'tests';

            case 'src_path': return $this->project ? $this->src : "{$this->path}{$this->src}";
            case 'sample_path': return $this->project ? $this->samples : "{$this->path}{$this->samples}";
            case 'test_path': return $this->project ? $this->tests : "{$this->path}{$this->tests}";
        }

        return parent::default($property);
    }
}