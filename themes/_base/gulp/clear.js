const del = require("del");

const appName = require('./appName');
const themeName = require('./themeName');
const task = require('./task');

module.exports = function clear() {
    return task(`clearPublic('${appName}', '${themeName}')`, function () {
        return del(`public/${appName}/${themeName}/**`);
    });
}