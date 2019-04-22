<?php

namespace Manadev\Data\Queries;

class Part
{
    // when cloning use the following constants:

    const DB = 'db';
    const FROM = 'from';
    const INNER_JOIN = 'innerJoin';
    const LEFT_JOIN = 'leftJoin';
    const VIRTUAL_JOIN = 'virtualJoin';
    const WHERE = 'where';
    const SELECT = 'select';
    const GROUP_BY = 'groupBy';
    const ORDER_BY = 'orderBy';
    const FACET_BY = 'facetBy';

    const FACETED_QUERY = [self::DB, self::FROM, self::INNER_JOIN, self::LEFT_JOIN, self::VIRTUAL_JOIN,
        self::WHERE, self::SELECT, self::FACET_BY, self::GROUP_BY, self::ORDER_BY];
    const QUERY = [self::DB, self::FROM, self::INNER_JOIN, self::LEFT_JOIN, self::VIRTUAL_JOIN,
        self::WHERE, self::SELECT, self::GROUP_BY, self::ORDER_BY];
    const NOT_COLUMNS = [self::DB, self::FROM, self::INNER_JOIN, self::LEFT_JOIN, self::VIRTUAL_JOIN, self::WHERE,
        self::GROUP_BY, self::ORDER_BY];
    const NOT_WHERE = [self::DB, self::FROM, self::INNER_JOIN, self::LEFT_JOIN, self::VIRTUAL_JOIN,
        self::SELECT, self::GROUP_BY, self::ORDER_BY];
    const NOT_WHERE_OR_JOIN = [self::DB, self::FROM, self::SELECT, self::GROUP_BY, self::ORDER_BY];

    const IDENTITY = [self::DB, self::FROM, self::INNER_JOIN, self::LEFT_JOIN, self::VIRTUAL_JOIN, self::WHERE];

    // inside resolver, use the same constants as above with one exception:
    // use JOIN instead of INNER_JOIN and LEFT_JOIN

    const JOIN = 'join';
}