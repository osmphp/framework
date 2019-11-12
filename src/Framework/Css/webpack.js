"use strict";

// standard libraries
const path = require('path');
const fs = require('fs');

// custom packages
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

class Builder {
    constructor(config, paths) {
        this.config = config;
        this.paths = paths;
    }

    build(target) {
        const entries = this.buildEntryGroup(target, 'css/styles.scss');
        if (!entries.length) {
            return null;
        }

        return {
            entry: {
                scripts: entries
            },
            module: {
                rules: [
                    {
                        test: /\.s?[ac]ss$/,
                        loaders: [
                            // {
                            //     loader: "file-loader",
                            //     options: {
                            //         name: this.paths.getPublicPath(target) + "/[hash].css"
                            //     }
                            // },
                            {
                                loader: MiniCssExtractPlugin.loader
                            },
                            {
                                loader: "css-loader", options: {
                                    sourceMap: true
                                }
                            },
                            {
                                loader: "sass-loader", options: {
                                    sourceMap: true,
                                    sassOptions: {
                                        includePaths: ['./node_modules']
                                    }
                                }
                            }
                        ]
                    }
                ]
            },
            plugins: [
                new MiniCssExtractPlugin({
                    filename: "styles.css"
                })
            ]
        };
    }

    buildEntryGroup(target, styleFileName) {
        const result = [];

        this.config.forEachModule(module => {
            this.config.forEachArea(target, area => {
                if (fs.existsSync(path.resolve(module.path, area.resource_path, styleFileName))) {
                    result.push(module.name + '/' + styleFileName);
                }
            });
        });

        this.config.forEachTheme(target, theme => {
            return this.config.forEachThemeDefinition(theme, definition => {
                const relevant = this.config.isRelevant(target, definition);

                if (relevant && fs.existsSync(path.resolve(definition.path, definition.name, styleFileName))) {
                    result.push(definition.name + '/' + styleFileName);
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