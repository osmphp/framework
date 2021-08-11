const {series} = require("gulp");

const clear = require('./clear');
const save = require('./save');
const call = require('./call');

module.exports = function buildTheme(appName, themeName) {
    return series(
        clear(appName, themeName),
        save(appName, themeName),
        call(appName, themeName)
    );
};