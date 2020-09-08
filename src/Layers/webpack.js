"use strict";

const StorageResourceBuilder = require('../WebPack/scripts/StorageResourceBuilder');

module.exports = target => new StorageResourceBuilder(
    'layers',
    require('../WebPack/scripts/config'),
    require('../WebPack/scripts/transform'),
    require('../WebPack/scripts/paths')
)
    .build(target);