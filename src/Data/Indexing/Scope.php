<?php

namespace Osm\Data\Indexing;

use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property int $mode @part
 *      If equals to Indexer::MODE_FULL, target <= source should be recaptured in full, without any limitations
 *      If equals to Indexer::MODE_PARTIAL, target <= source be recaptured with applied filters according to
 *          $sources property
 * @property bool $no_transaction @part
 */
class Scope extends Object_
{
    /**
     * @required @part
     *
     * If $mode = Indexer::MODE_FULL, target <= source should be recaptured in full ignoring this property
     *
     * Otherwise, specifies a indexer record ID for each of source tables. If indexer record ID is provided,
     * then filter should be applied on source table to process only records mentioned in corresponding
     * notification table.
     *
     * If several indexer record IDs are specified, their filter conditioned are combined using logical OR
     *
     * If no indexer record IDs are specified, no indexing should occur.
     *
     * @var int[]
     */
    public $sources = [];
}