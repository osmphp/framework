const {watch, series, parallel, src, dest} = require('gulp');
const {rollup} = require('gulp-rollup-2');
const {nodeResolve} = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');
const del = require('del');

const appName = process.env.GULP_APP;
const themeName = process.env.GULP_THEME;

function buildTheme() {
    return series(
        collect(),
        clear(),
        parallel(
            files('images'),
            files('fonts'),
            files('files'),
            js(),
            css()
        )
    );
}

function collect() {
    let tasks = [collectFrom(`themes/${themeName}`)];
    return series(...tasks);
}

function collectFrom(sourcePath) {
    function fn() {
        return src(`${sourcePath}/**`)
            .pipe(dest(`temp/${appName}/${themeName}`));
    }
    return exports[fn.displayName = `collectFrom('${appName}', '${themeName}', '${sourcePath}')`] = fn;
}

function clear() {
    function fn() {
        return del(`public/${appName}/${themeName}/**`);
    }
    return exports[fn.displayName = `clearPublic('${appName}', '${themeName}')`] = fn;
}

function files(path) {
    function fn() {
        return src([
                `temp/${appName}/${themeName}/${path}/**`,
            ])
            .pipe(dest(`public/${appName}/${themeName}/${path}`));
    }
    return exports[fn.displayName = `files('${appName}', '${themeName}', '${path}')`] = fn;
}

function js() {
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

function css() {
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

function watchTheme() {
    function fn() {
        watch(['_*/**', 'composer.lock', 'package-lock.json'],
            collect(appName, themeName));
        watch(`temp/${appName}/${themeName}/js/*.js`,
            js(appName, themeName));
        watch(`temp/${appName}/${themeName}/css/*.css`,
            css(appName, themeName));

        watch([
            `temp/${appName}/${themeName}/**`,
            `!temp/${appName}/${themeName}/js/**`,
            `!temp/${appName}/${themeName}/css/**`,
            `!temp/${appName}/${themeName}/views/**`,
        ], files(appName, themeName));
    }
    return exports[fn.displayName = `watchTheme('${appName}', '${themeName}')`] = fn;
}

exports.default = buildTheme();