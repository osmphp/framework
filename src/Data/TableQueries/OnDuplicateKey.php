<?php

namespace Osm\Data\TableQueries;

class OnDuplicateKey
{
    const ERROR = 0;
    const IGNORE = 1;
    const UPDATE = 2;
}