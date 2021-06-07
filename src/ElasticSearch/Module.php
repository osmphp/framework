<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Logs\Hints\LogSettings;
use Osm\Framework\Logs\Logs;
use Osm\Framework\Search\Field;
use Osm\Framework\Search\Filter;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Search\Module::class,
        \Osm\Framework\Logs\Module::class,
    ];

    public static array $traits = [
        Logs::class => Traits\LogsTrait::class,
        LogSettings::class => Traits\LogSettingsTrait::class,

        Field::class => Traits\FieldTrait::class,
        Field\Int_::class => Traits\FieldTrait\Int_::class,
        Field\String_::class => Traits\FieldTrait\String_::class,
        Field\Float_::class => Traits\FieldTrait\Float_::class,
        Field\Bool_::class => Traits\FieldTrait\Bool_::class,

        Filter::class => Traits\FilterTrait::class,
        Filter\Logical::class => Traits\FilterTrait\Logical::class,
        Filter\Field::class => Traits\FilterTrait\Field::class,
    ];
}