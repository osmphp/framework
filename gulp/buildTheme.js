const {execSync} = require("child_process");
const {series} = require("gulp");

const clear = require('./clear');
const save = require('./save');
const collect = require('./collect');
const call = require('./call');
const osmt = require('./osmt');

module.exports = function buildTheme(appName, themeName) {
    let config = JSON.parse(
        execSync(`php ${osmt} config:gulp --app=${appName}`).toString());

    return series(
        clear(appName, themeName),
        save(appName, themeName, config),
        collect(appName, themeName, config),
        call(appName, themeName, config)
    );
};