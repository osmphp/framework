"use strict";

// standard libraries
const path = require('path');

class Paths {
    getEnvPath(path1, path2, sep) {
        if (!sep) {
            sep = path.sep;
        }

        return process.env.APP_ENV == 'production'
            ? path1 + sep + path2
            : path1 + sep + process.env.APP_ENV + sep + path2;
    }

    getPublicPath(target) {
        return this.getEnvPath('public', target.area + '/' + target.theme, '/');
    }

    getTempPath(path) {
        return 'temp/' + process.env.APP_ENV + '/' + path;
    }

    getStoragePath(target, assetType) {
        const projectPath = this.getTempPath(assetType + '/' + target.area + '/' + target.theme);
        return path.relative(this.getPublicPath(target), projectPath);
    }

    getProjectPath(absolutePath) {
        return path.relative(process.cwd(), absolutePath).replace(/\\/g, '/');
    }
}

module.exports = new Paths();