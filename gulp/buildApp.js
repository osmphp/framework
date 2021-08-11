const {series} = require("gulp");

const compile = require('./compile');
const refresh = require('./refresh');

module.exports = function buildApp(appName) {
    return series(
        compile(appName),
        refresh(appName),
    );
}
