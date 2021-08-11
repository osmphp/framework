const del = require("del");

const task = require('./task');

module.exports = function clear(appName, themeName) {
    return task(`clearTemp('${appName}', '${themeName}')`, function () {
        return del(`temp/${appName}/${themeName}/**`);
    });
}