<?php

namespace Osm\Data\Tables;

use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;
use Osm\Framework\Db\Db;
use Osm\Framework\Db\MySql;
use Osm\Framework\Migrations\InstallationQuestion;

class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        Db::class => Traits\DbTrait::class,
        MySql::class => Traits\MySqlTrait::class,
        InstallationQuestion::class => Traits\InstallationQuestionTrait::class,
    ];
}