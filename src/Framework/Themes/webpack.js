"use strict";

// standard libraries
const path = require('path');

// custom packages
const JohnnyResolverPlugin = require('../WebPack/scripts/ResolverPlugin');

class Builder {
    constructor(config, paths, projectPathParser, search) {
        this.config = config;
        this.paths = paths;
        this.projectPathParser = projectPathParser;
        this.search = search;
    }

    build(target) {
        return {
            resolve: {
                plugins: [
                    new JohnnyResolverPlugin(target, this.config, this.paths, this.projectPathParser, this.search)
                ]
            }
        };
    }
}

module.exports = target => new Builder(
        require('../WebPack/scripts/config'),
        require('../WebPack/scripts/paths'),
        require('../WebPack/scripts/projectPathParser'),
        require('../WebPack/scripts/search')
    ).build(target);