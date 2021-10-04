<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Field;

use Osm\Core\Attributes\UseIn;
use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\ElasticSearch\Traits\FieldTrait;
use Osm\Framework\Search\Field\Float_;
use function Osm\__;

#[UseIn(Float_::class)]
trait FloatTrait
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        /* @var Float_|FloatTrait $this */
        if ($this->searchable) {
            throw new NotSupported(__(
                "Search fields of ':type' type can't be searchable",
                ['type' => $this->type]));
        }

        return [
            $this->name => [
                'type' => 'float',
            ]
        ];
    }
}