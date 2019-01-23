"use strict";

const ResourceBuilder = require('./ResourceBuilder');

class AssetResourceBuilder extends ResourceBuilder {
    getModuleTargetPath(target, module) {
        return module.name + '/[1]';
    }

    getThemeTargetPath(target, componentName) {
        return componentName + '/[1]';
    }
}

module.exports = AssetResourceBuilder;