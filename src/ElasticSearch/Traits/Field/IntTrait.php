<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Field;

use Osm\Core\Attributes\UseIn;
use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\ElasticSearch\Traits\FieldTrait;
use Osm\Framework\Search\Field\Int_;
use function Osm\__;

#[UseIn(Int_::class)]
trait IntTrait
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        /* @var Int_|IntTrait $this */
        if ($this->searchable) {
            throw new NotSupported(__(
                "Search fields of ':type' type can't be searchable",
                ['type' => $this->type]));
        }

        return [
            $this->name => [
                'type' => 'integer',
            ]
        ];
    }
}