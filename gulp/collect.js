const {series} = require("gulp");

const themePaths = require('./themePaths');
const collectFrom = require('./collectFrom');

module.exports = function collect(appName, themeName, config) {
    return series(...themePaths(themeName, config).map(
        path => collectFrom(appName, themeName, path)));
}
