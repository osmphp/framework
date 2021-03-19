const {watch, series, parallel} = require('gulp');
const fs = require('fs');

const config = {
    'Osm_Framework_Samples': ['_front__tailwind']
}

const osmc = fs.existsSync('vendor/osmphp/core/bin/compile.php')
    ? 'vendor/osmphp/core/bin/compile.php'
    : 'bin/compile.php';
const osmt = fs.existsSync('vendor/osmphp/framework/bin/tools.php')
    ? 'vendor/osmphp/core/bin/tools.php'
    : 'bin/tools.php';
function osm(appName) {
    return fs.existsSync(`vendor/osmphp/framework/bin/run.php`)
    ? `vendor/osmphp/framework/bin/run.php --app=${appName}`
    : `bin/run.php --app=${appName}`;
}

function buildApp(appName) {
    function fn(cb) {
        return cb();
    }
    return exports[fn.displayName = `buildApp('${appName}')`] = fn;
}

function watchApp(appName) {
    function fn() {
    }
    return exports[fn.displayName = `watchApp('${appName}')`] = fn;
}

function buildTheme(appName, themeName) {
    function fn(cb) {
        return cb();
    }
    return exports[fn.displayName = `buildTheme('${appName}', '${themeName}')`] = fn;
}

function watchTheme(appName, themeName) {
    function fn() {
    }
    return exports[fn.displayName = `watchTheme('${appName}', '${themeName}')`] = fn;
}

//region Gulp tasks
let buildTasks = [];
let watchTasks = [];

for (let appName in config) {
    if (!config.hasOwnProperty(appName)) continue;

    let buildAppTasks = [buildApp(appName)];
    let watchAppTasks = [watchApp(appName)];

    config[appName].forEach(function(themeName) {
        buildAppTasks.push(buildTheme(appName, themeName));
        watchAppTasks.push(watchTheme(appName, themeName));
    });

    buildTasks.push(exports[`build:${appName}`] = series(...buildAppTasks));
    watchTasks.push(exports[`watch:${appName}`] = parallel(...watchAppTasks));
}

exports.default = exports.build = parallel(...buildTasks);
exports.watch = parallel(...watchTasks);
//endregion