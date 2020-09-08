"use strict";

// standard libraries
const path = require('path');

class ResolverPlugin {
    constructor(target, config, paths, projectPathParser, search) {
        this.target = target;
        this.config = config;
        this.paths = paths;
        this.projectPathParser = projectPathParser;
        this.search = search;
    }

    apply(resolver) {
        const target = resolver.ensureHook('undescribed-raw-file');
        resolver.getHook('before-resolve').tapAsync("OsmResolverPlugin", (resource, resolveContext, callback) => {
            const request = this.resolveRequest(this.target, resource);
            if (request) {
                resource.path = request;
            }
            resolver.doResolve(target, resource, "using theme", resolveContext, callback);
        });
    }

    resolveRequest(target, resource) {
        if (path.isAbsolute(resource.request)) {
            return;
        }

        return this.resolveProceed(target, resource) ||
            this.resolveRelativePath(target, resource) ||
            this.resolveModularPath(target, resource);
    }

    resolveProceed(target, resource){
        const match = resource.request.match(/^@proceed\(([^\)]+)\)$/);
        if (!match) {
            return;
        }

        const projectPath = this.paths.getProjectPath(path.resolve(resource.path, match[1]));
        const themeResource = this.projectPathParser.parseThemeResource(target, projectPath);
        if (themeResource) {
            return this.search.search(themeResource.path, target, { mostDetailedTheme: themeResource.theme.parent_theme });
        }

    }

    resolveRelativePath(target, resource) {
        if (!resource.request.startsWith('.')) {
            return;
        }

        const projectPath = this.paths.getProjectPath(path.resolve(resource.path, resource.request));

        const moduleResource = this.projectPathParser.parseModuleResource(target, projectPath);
        if (moduleResource) {
            return this.search.search(moduleResource.path, target, { mostAbstractArea: moduleResource.area.name });
        }

        const themeResource = this.projectPathParser.parseThemeResource(target, projectPath);
        if (themeResource) {
            return this.search.search(themeResource.path, target);
        }
    }

    resolveModularPath(target, resource) {
        const resourcePath = resource.request;

        const container = this.search.getContainerName(resourcePath);
        if (!container || container.indexOf('_') == -1 || container[0] != container[0].toUpperCase()) {
            return this.search.search(resourcePath, target);
        }
        const modulePath = resourcePath.substr(container.length + 1);

        const ext = path.extname(resourcePath).toLowerCase();
        if (ext == '.css' || ext == '.scss') {
            if (modulePath.startsWith('css/')) {
                return this.search.search(resourcePath, target);
            }

            return this.search.search(container + '/css/' + modulePath, target);
        }
        if (!ext || ext == '.js' || ext == '.json') {
            if (modulePath.startsWith('js/') || modulePath.startsWith('critical-js/')) {
                return this.search.search(resourcePath, target);
            }

            const scriptArea = this.getScriptArea(resource, target);
            if (!scriptArea) {
                return this.search.search(resourcePath, target);
            }

            return this.search.search(container + '/' + scriptArea + '/' + modulePath, target);
        }

        return this.search.search(resourcePath, target);
    }

    getScriptArea(resource, target) {
        const projectPath = this.paths.getProjectPath(resource.context.issuer);

        const moduleResource = this.projectPathParser.parseModuleResource(target, projectPath);
        if (moduleResource) {
            return this.getResourceScriptArea(moduleResource.path);
        }

        const themeResource = this.projectPathParser.parseThemeResource(target, projectPath);
        if (themeResource) {
            return this.getResourceScriptArea(themeResource.path);
        }
    }

    getResourceScriptArea(resourcePath) {
        const container = this.search.getContainerName(resourcePath);
        if (!container) {
            return;
        }

        const modulePath = resourcePath.substr(container.length + 1);
        if (modulePath.startsWith('js/')) {
            return 'js';
        }

        if (modulePath.startsWith('critical-js/')) {
            return 'critical-js';
        }
    }
}

module.exports = ResolverPlugin;