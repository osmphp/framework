"use strict";

class ProjectPathParser {
    constructor(config) {
        this.config = config;
    }

    parseModuleResource(target, projectPath) {
        return this.config.forEachModule(module => {
            if (!projectPath.startsWith(module.path)) {
                return;
            }

            const resourcePathInsideModule = projectPath.substr(module.path.length + 1);

            return this.config.forEachArea(target, area => {
                if (!resourcePathInsideModule.startsWith(area.resource_path)) {
                    return;
                }

                return {
                    area: area,
                    path: module.name + '/' + resourcePathInsideModule.substr(area.resource_path.length + 1)
                };
            });

        });
    }

    parseThemeResource(target, projectPath) {
        return this.config.forEachTheme(target, theme => {
            return this.config.forEachThemeDefinition(theme, themeDefinition => {
                if (!this.config.isRelevant(target, themeDefinition)) {
                    return;
                }

                if (projectPath.startsWith(themeDefinition.path)) {
                    return {
                        theme: theme,
                        themeDefinition: themeDefinition,
                        path: projectPath.substr(themeDefinition.path.length + 1)
                    }
                }
            });
        });
    }

    parseNodeModuleResource(target, projectPath) {
        if (projectPath.startsWith('node_modules/')) {
            return {
                path: projectPath.substr('node_modules/'.length)
            };
        }
    }
}

module.exports = new ProjectPathParser(require('./config'));