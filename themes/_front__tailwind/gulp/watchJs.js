const {watch} = require("gulp");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');
const js = require('./js');

module.exports = function watchJs() {
    return task(`watchJs('${appName}', '${themeName}')`, function () {
        let dir = `${appName}/${themeName}/js`;

        let patterns = [`temp/${dir}/theme/**`, `temp/${dir}/scripts.js`];
        config.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return watch(patterns, js());
    });
}