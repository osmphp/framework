const {dest} = require("gulp");
const watch = require("gulp-watch");
const print = require('gulp-print').default;
const {relative} = require("path");
const fs = require("fs");
const through = require('through2');

const task = require('./task');
const themePaths = require('./themePaths');

module.exports = function watchCollect(appName, themeName) {
    return task(`watchCollect('${appName}', '${themeName}')`, function () {
        let config = JSON.parse(fs.readFileSync(
            `temp/${appName}/${themeName}/config.json`));
        let paths = themePaths(themeName, config);

        return watch(paths.map(path => `${path}/**`))
            .pipe(through.obj(function (file, _, cb) {
                paths.forEach(path => {
                    const filename = `${path}/${relative(file.base, file.path)
                        .replace(/\\/g, '/')}`;

                    if (fs.existsSync(filename)) {
                        file.contents = fs.readFileSync(filename);
                    }
                });
                cb(null, file);
            }))
            .pipe(print(filepath => `collect(${filepath})`))
            .pipe(dest(`temp/${appName}/${themeName}`));
    });
}