"use strict";

// standard libraries
const fs = require('fs');
const path = require('path');

// custom packages
const _ = require('lodash');
const merge = require('webpack-merge');

const VersionBumpPlugin = require('./scripts/VersionBumpPlugin');
const AssetResourceBuilder = require('./scripts/AssetResourceBuilder');

class Builder {
    constructor(config, paths, projectPathParser) {
        this.config = config;
        this.paths = paths;
        this.projectPathParser = projectPathParser;
    }

    build() {
        return _.filter(this.config.targets.map(target => this.buildTarget(target)), config => config.entry);
    }

    buildTarget(target) {
        let config = {
            mode: process.env.APP_ENV == 'production' ? 'production' : 'development',
            devtool: parseInt(process.env.NO_WEBPACK_SOURCE_MAPS) ? '' : "source-map",
            stats: "minimal",
            module: {
                rules: [
                    {
                        test: /\.(png|svg|jpg|gif|woff|woff2|eot|ttf|otf)$/,
                        use: [
                            {
                                loader: 'file-loader',
                                options: {
                                    name: absolutePath => this.getAssetPath(target, absolutePath)
                                }
                            }
                        ]
                    }
                ]
            },
            plugins: [
                new VersionBumpPlugin(this.paths.getPublicPath(target) + '/version.txt')
            ],
            performance: {
              hints: false
            }
        };

        config = merge(config, (new AssetResourceBuilder(
                'public',
                require('./scripts/config'),
                require('./scripts/transform'),
                require('./scripts/paths')
            )).build(target));

        this.config.forEachModule(module => {
            if (fs.existsSync(path.resolve(module.path, 'webpack.js'))) {
                config = merge(config, require(path.resolve(module.path, 'webpack.js'))(target));
            }
        });

        return config;
    }

    getAssetPath(target, absolutePath) {
        const projectPath = this.paths.getProjectPath(absolutePath);
        const moduleResource = this.projectPathParser.parseModuleResource(target, projectPath);
        if (moduleResource) {
            return this.removePublicDirectoryFromPath(moduleResource.path);
        }

        const themeResource = this.projectPathParser.parseThemeResource(target, projectPath);
        if (themeResource) {
            return this.removePublicDirectoryFromPath(themeResource.path);
        }

        const nodeModuleResource = this.projectPathParser.parseNodeModuleResource(target, projectPath);
        if (nodeModuleResource) {
            return nodeModuleResource.path;
        }

        throw 'Not supported asset location: ' + absolutePath;
    }

    removePublicDirectoryFromPath(path) {
        return path.replace(/^([^\/]+)\/public(.*)$/g, '$1$2');
    }
}

require('./scripts/bootstrapper').run();
const config = new Builder(
        require('./scripts/config'),
        require('./scripts/paths'),
        require('./scripts/projectPathParser')
    )
    .build();
module.exports = config;