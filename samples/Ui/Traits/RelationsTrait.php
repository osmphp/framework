<?php

namespace Osm\Samples\Ui\Traits;

use Osm\Data\TableQueries\TableQuery;

trait RelationsTrait
{
    /**
     * @param TableQuery $query
     * @param $contact
     * @param $image
     */
    public function t_contacts__image($query, $contact, $image) {
        $query->leftJoin("files AS {$image}",
            "{$contact}.image = {$image}.id");
    }

}