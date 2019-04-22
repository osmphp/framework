<?php

namespace Manadev\Data\Tables\Traits;

trait InstallationQuestionTrait
{
    protected function around_usesDb(callable $proceed) {
        return true;
    }
}