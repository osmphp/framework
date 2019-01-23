"use strict";

// standard libraries
const path = require('path');
const fs = require('fs');

// custom packages
const CopyWebpackPlugin = require('copy-webpack-plugin')
const glob = require('glob');

class ResourceBuilder {
    constructor(assetType, config, transform, paths) {
        this.assetType = assetType;
        this.config = config;
        this.transform = transform;
        this.paths = paths;
    }

    build(target) {
        return {
            plugins: [
                new CopyWebpackPlugin(this.getPatterns(target))
            ]
        };
    }

    getModuleTargetPath(target, module) {
        throw 'Not implemented';
    }

    getThemeTargetPath(target, componentName) {
        throw 'Not implemented';
    }

    getPatterns(target) {
        return this.getModulePatterns(target).concat(
            this.getThemePatterns(target));
    }

    getModulePatterns(target) {
        let patterns = [];

        this.config.forEachModule(module => {
            this.config.forEachArea(target, area => {
                const projectPath = module.path + '/' + area.resource_path + '/' + this.assetType;

                if (!fs.existsSync(path.resolve(projectPath))) {
                    return;
                }

                patterns.push({
                    from: projectPath,
                    to: this.getModuleTargetPath(target, module),
                    test: new RegExp('[\\\\\\/]' + this.assetType + '[\\\\\\/](.+)$'),
                    transform: (content, path) => {
                        return this.transform.transform(target, content, path)
                    }
                });
            });
        });

        return patterns;
    }

    getThemePatterns(target) {
        let patterns = [];

        this.config.forEachTheme(target, theme => {
            return this.config.forEachThemeDefinition(theme, themeDefinition => {
                if (!this.config.isRelevant(target, themeDefinition)) {
                    return;
                }

                glob.sync(themeDefinition.path + '/*/' + this.assetType).forEach(absolutePath => {
                    const projectPath = this.paths.getProjectPath(absolutePath);
                    const componentName = projectPath.substring(themeDefinition.path.length + 1,
                        projectPath.length - this.assetType.length - 1);

                    patterns.push({
                        from: projectPath,
                        to: this.getThemeTargetPath(target, componentName),
                        test: new RegExp('[\\\\\\/]' + this.assetType + '[\\\\\\/](.+)$'),
                        transform: (content, path) => {
                            return this.transform.transform(target, content, path)
                        }
                    });
                });
            });
        });

        return patterns;
    }
}

module.exports = ResourceBuilder;