"use strict";

const ResourceBuilder = require('./ResourceBuilder');

class StorageResourceBuilder extends ResourceBuilder {
    getModuleTargetPath(target, module) {
        return this.paths.getStoragePath(target, this.assetType) + '/' + module.name + '/[1]';
    }

    getThemeTargetPath(target, componentName) {
        return this.paths.getStoragePath(target, this.assetType) + '/' + componentName + '/[1]';
    }
}

module.exports = StorageResourceBuilder;