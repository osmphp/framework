<?php

namespace Manadev\Data\Tables;

use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;
use Manadev\Framework\Db\Db;
use Manadev\Framework\Db\MySql;
use Manadev\Framework\Migrations\InstallationQuestion;

class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        Db::class => Traits\DbTrait::class,
        MySql::class => Traits\MySqlTrait::class,
        InstallationQuestion::class => Traits\InstallationQuestionTrait::class,
    ];
}