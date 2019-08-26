<?php

namespace Osm\Data\Tables\Traits;

trait InstallationQuestionTrait
{
    protected function around_usesDb(callable $proceed) {
        return true;
    }
}