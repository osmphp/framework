const {series} = require("gulp");

const themePaths = require('./themePaths');
const collectFrom = require('./collectFrom');

module.exports = function collect(appName, themeName) {
    return series(...themePaths(themeName).map(
        path => collectFrom(appName, themeName, path)));
}
