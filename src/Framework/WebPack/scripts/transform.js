"use strict";

// standard libraries
const fs = require('fs');

class Transform {
    constructor(config, paths, search, projectPathParser) {
        this.config = config;
        this.paths = paths;
        this.search = search;
        this.projectPathParser = projectPathParser;
    }

    transform(target, content, path) {
        const projectPath = this.paths.getProjectPath(path);
        const overridePath = this.transformProjectPath(target, projectPath);

        if (!overridePath) {
            return content;
        }

        if (overridePath === path) {
            return content;
        }

        return fs.readFileSync(overridePath);
    }

    transformProjectPath(target, projectPath) {
        const moduleResource = this.projectPathParser.parseModuleResource(target, projectPath);
        if (moduleResource) {
            return this.search.search(moduleResource.path, target, {mostAbstractArea: moduleResource.area.name});
        }

        const themeResource = this.projectPathParser.parseThemeResource(target, projectPath);
        if (themeResource) {
            return this.search.search(themeResource.path, target);
        }
    }
}

module.exports = new Transform(
    require('./config'),
    require('./paths'),
    require('./search'),
    require('./projectPathParser')
);