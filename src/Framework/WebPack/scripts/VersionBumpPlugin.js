"use strict";

const fs = require('fs');

module.exports = class VersionBumpPlugin {
    constructor(path) {
        this.path = path;
    }

    apply(compiler) {
        compiler.hooks.done.tap('VersionBumpPlugin', (stats) => {
            fs.writeFileSync(this.path, this.randomStr());
        });
    }

    randomStr() {
        let text = "";
        let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (let i = 0; i < 8; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
};