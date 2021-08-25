const {src, dest} = require("gulp");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');

module.exports = function files(path) {
    return task(`files('${appName}', '${themeName}', '${path}')`, function () {
        let dir = `${appName}/${themeName}/${path}`;

        let patterns = [`temp/${dir}/theme/**`];
        config.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return src(patterns, {base: `temp/${dir}`})
            .pipe(dest(`public/${dir}`));
    });
}