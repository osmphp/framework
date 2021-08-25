const {dest} = require('gulp');
const watchSrc = require('gulp-watch');
const fs = require('fs');
const through = require('through2');
const {relative} = require('path');
const print = require('gulp-print').default;

const appName = process.env.GULP_APP;
const themeName = process.env.GULP_THEME;

const build = require('./build');
const watch = require('./watch');

function watchCollect() {
    function fn () {
        let paths = themePaths();
        return watchSrc(paths.map(path => `${path}/**`))
            .pipe(through.obj(function(file, _, cb) {
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
    }
    return exports[fn.displayName = `watchCollect('${appName}', '${themeName}')`] = fn;
}

global.tasks = {};
exports.default = build();
exports.watch = watch();
Object.assign(exports, global.tasks);