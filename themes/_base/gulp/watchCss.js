const {watch} = require("gulp");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');
const css = require('./css');

module.exports = function watchCss() {
    return task(`watchCss('${appName}', '${themeName}')`, function () {
        let dir = `${appName}/${themeName}/css`;

        let patterns = [`temp/${dir}/theme/**`, `temp/${dir}/styles.css`];
        config.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return watch(patterns, css());
    });
}