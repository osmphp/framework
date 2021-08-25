const {dest} = require("gulp");
const watch = require("gulp-watch");
const print = require('gulp-print').default;

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');

module.exports = function watchFiles(path) {
    return task(`watchFiles('${appName}', '${themeName}', '${path}')`, function () {
        let dir = `${appName}/${themeName}/${path}`;

        let patterns = [`temp/${dir}/theme/**`];
        config.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return watch(patterns, {base: `temp/${dir}`})
            .pipe(print(filepath => `files(${filepath})`))
            .pipe(dest(`public/${dir}`));
    });
}