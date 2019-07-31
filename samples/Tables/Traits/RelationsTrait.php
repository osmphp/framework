<?php

namespace Manadev\Samples\Tables\Traits;

use Manadev\Data\TableQueries\TableQuery;

trait RelationsTrait
{
    /**
     * @see \Manadev\Tests\Data\TableQueries\RelationTest
     *
     * @param TableQuery $query
     * @param $task
     * @param $user
     */
    public function test_tasks__user($query, $task, $user) {
        $query->leftJoin("test_users AS {$user}", "{$task}.user = {$user}.id");
    }

    /**
     * @see \Manadev\Tests\Data\TableSheets\BasicSheetTest
     *
     * @param TableQuery $query
     * @param $user
     * @param $account
     */
    public function test_users__final__account($query, $user, $account) {
        $query->leftJoin("test_accounts AS {$account}", "{$user}.account = {$account}.id");
    }


}