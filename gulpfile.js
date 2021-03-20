const {watch, series, parallel, src, dest} = require('gulp');
const fs = require('fs');
const {rollup} = require('gulp-rollup-2');
const {nodeResolve} = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');
const del = require('del');

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
    return series(
        compile(appName),
        refresh(appName),
    );
}

function compile(appName) {
    function fn(cb) {
        return cb();
    }
    return exports[fn.displayName = `compile('${appName}')`] = fn;
}

function refresh(appName) {
    function fn(cb) {
        return cb();
    }
    return exports[fn.displayName = `refresh('${appName}')`] = fn;
}

function watchApp(appName) {
    function fn() {
        watch(['src/**', 'samples/**', 'composer.lock'], buildApp(appName));
    }
    return exports[fn.displayName = `watchApp('${appName}')`] = fn;
}

function buildTheme(appName, themeName) {
    return series(
        collect(appName, themeName),
        clearPublic(appName, themeName),
        files(appName, themeName),
        js(appName, themeName),
        css(appName, themeName)
    );
}

function collect(appName, themeName) {
    function fn(cb) {
        return cb();
    }
    return exports[fn.displayName = `collect('${appName}', '${themeName}')`] = fn;
}

function clearPublic(appName, themeName) {
    function fn() {
        return del(`public/${appName}/${themeName}/**`);
    }
    return exports[fn.displayName = `clearPublic('${appName}', '${themeName}')`] = fn;
}

function files(appName, themeName) {
    function fn() {
        return src([
                `temp/${appName}/${themeName}/**`,
                `!temp/${appName}/${themeName}/js/**`,
                `!temp/${appName}/${themeName}/css/**`,
                `!temp/${appName}/${themeName}/views/**`,
            ])
            .pipe(dest(`public/${appName}/${themeName}`));
    }
    return exports[fn.displayName = `files('${appName}', '${themeName}')`] = fn;
}

function js(appName, themeName) {
    function fn() {
        return src(`temp/${appName}/${themeName}/js/*.js`)
            .pipe(sourcemaps.init())
            .pipe(rollup({
                input: `temp/${appName}/${themeName}/js/index.js`,
                output: {
                    file: 'scripts.js',
                    format: 'es',
                },
                plugins: [commonjs(), nodeResolve()]
            }))
            .pipe(sourcemaps.write('.'))
            .pipe(dest(`public/${appName}/${themeName}`));
    }
    return exports[fn.displayName = `js('${appName}', '${themeName}')`] = fn;
}

function css(appName, themeName) {
    function fn() {
        return src(`temp/${appName}/${themeName}/css/styles.css`)
            .pipe(sourcemaps.init())
            .pipe(postcss([
                require('postcss-import'),
                require('tailwindcss'),
                require('autoprefixer')
            ]))
            .pipe(sourcemaps.write('.'))
            .pipe(dest(`public/${appName}/${themeName}`));
    }
    return exports[fn.displayName = `css('${appName}', '${themeName}')`] = fn;
}

function watchTheme(appName, themeName) {
    function fn() {
        watch(['_*/**', 'composer.lock', 'package-lock.json'],
            collect(appName, themeName));
        watch(`temp/${appName}/${themeName}/js/*.js`,
            js(appName, themeName));
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