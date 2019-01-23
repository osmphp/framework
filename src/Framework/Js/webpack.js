"use strict";

// standard libraries
const path = require('path');
const fs = require('fs');

// custom packages
const _ = require('lodash');

class Builder {
    constructor(config, paths) {
        this.config = config;
        this.paths = paths;
    }

    build(target) {
        const entryGroups = this.buildEntryGroups(target);
        if (!Object.keys(entryGroups).length) {
            return null;
        }

        return {
            entry: entryGroups,
            output: {
                path: path.resolve(process.cwd(), this.paths.getPublicPath(target)),
                filename: "[name].js"
            }
        };
    }

    buildEntryGroups(target) {
        const groups = {
            critical: 'critical-js',
            scripts: 'js'
        };

        let result = {};

        _.forEach(groups, (scriptDirectory, name) => {
            const entries = this.buildEntryGroup(target, scriptDirectory);
            if (entries.length) {
                result[name] = entries;
            }
        });

        return result;
    }

    buildEntryGroup(target, scriptDirectory) {
        const result = [];

        //if (process.env.APP_ENV == 'production') {
        //result.push("@babel/polyfill");
        //}

        this.config.forEachModule(module => {
            this.config.forEachArea(target, area => {
                if (fs.existsSync(path.resolve(module.path, area.resource_path, scriptDirectory, 'index.js'))) {
                    result.push(module.name + '/' + scriptDirectory + '/index.js');
                }
            });
        });

        this.config.forEachTheme(target, theme => {
            return this.config.forEachThemeDefinition(theme, definition => {
                const relevant = this.config.isRelevant(target, definition);

                if (relevant && fs.existsSync(path.resolve(definition.path, definition.name, scriptDirectory, 'index.js'))) {
                    result.push(definition.name + '/' + scriptDirectory + '/index.js');
                    return true;
                }
            });
        });

        return result;
    }
}

module.exports = target => new Builder(
        require('../WebPack/scripts/config'),
        require('../WebPack/scripts/paths')
    )
    .build(target);