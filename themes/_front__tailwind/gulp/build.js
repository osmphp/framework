const {series, parallel} = require("gulp");

const clear = require('./clear');
const importJsModules = require('./importJsModules');
const importCssModules = require('./importCssModules');
const files = require('./files');
const js = require('./js');
const css = require('./css');
const bump = require("./bump");

module.exports = function build() {
    return series(
        clear(),
        importJsModules(),
        importCssModules(),
        bump.once(),
        parallel(
            files('images'),
            files('fonts'),
            files('files'),
            js(),
            css()
        )
    );
}
