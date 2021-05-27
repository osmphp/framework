<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Logs\Hints\LogSettings;
use Osm\Framework\Logs\Logs;
use Osm\Framework\Search\Fields;
use Osm\Framework\Search\Filters;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Search\Module::class,
        \Osm\Framework\Logs\Module::class,
    ];

    public static array $traits = [
        Logs::class => Traits\LogsTrait::class,
        LogSettings::class => Traits\LogSettingsTrait::class,

        Fields\Field::class => Traits\Fields\FieldTrait::class,
        Fields\Int_::class => Traits\Fields\IntFieldTrait::class,
        Fields\String_::class => Traits\Fields\StringFieldTrait::class,

        Filters\Filter::class => Traits\Filters\FilterTrait::class,
        Filters\And_::class => Traits\Filters\AndTrait::class,
        Filters\Field::class => Traits\Filters\FieldTrait::class,
    ];
}