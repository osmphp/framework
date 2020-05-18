"use strict";

// standard libraries
const fs = require('fs');
const path = require('path');

class Search {
    constructor(config) {
        this.config = config;
    }

    search(resourcePath, target, options = {}) {
        return this.searchInThemes(resourcePath, target, options) ||
            this.searchInModules(resourcePath, target)

    }

    searchInThemes(resourcePath, target, options) {
        return this.config.forEachTheme(target, theme => {
            if (options.hasOwnProperty('mostDetailedTheme') && theme.name !== options.mostDetailedTheme) {
                return;
            }

            return this.config.forEachThemeDefinition(theme, themeDefinition => {
                if (!this.config.isRelevant(target, themeDefinition, options.mostAbstractArea)) {
                    return;
                }

                return this.searchFile(path.resolve(themeDefinition.path, resourcePath));
            });
        });
    }

    searchInModules(resourcePath, target) {
        const moduleResource = this.getModuleResource(target, resourcePath);
        if (!moduleResource) {
            return;
        }

        return this.config.forEachArea(target, area => {
            return this.searchFile(path.resolve(moduleResource.module.path, area.resource_path, moduleResource.path));
        });
    }

    searchFile(absolutePath) {
        if (fs.existsSync(absolutePath)) {
            return absolutePath;
        }

        if (!path.extname(absolutePath)) {
            for (const extension of ['.js', '.json', path.sep + 'index.js']) {
                if (fs.existsSync(absolutePath + extension)) {
                    return absolutePath + extension;
                }
            }
        }
    }

    getModuleResource(target, resourcePath) {
        const container = this.getContainerName(resourcePath);
        if (!container) {
            return;
        }

        if (this.config.modules.has(container)) {
            return {
                module: this.config.modules.get(container),
                path: resourcePath.substr(container.length + 1)
            }
        }
    }

    getContainerName(resourcePath) {
        const pos = resourcePath.indexOf('/');
        if (pos !== -1) {
            return resourcePath.substr(0, pos);
        }
    }
}

module.exports = new Search(require('./config'));