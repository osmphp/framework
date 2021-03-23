const {watch, series, parallel, src, dest} = require('gulp');
const fs = require('fs');
const {rollup} = require('gulp-rollup-2');
const {nodeResolve} = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');
const del = require('del');
var through = require('through2');
const replace = require('gulp-replace');
const json = require('./config.json');

const appName = process.env.GULP_APP;
const themeName = process.env.GULP_THEME;

function buildTheme() {
    return series(
        collect(),
        clear(),
        importJsModules(),
        importCssModules(),
        parallel(
            files('images'),
            files('fonts'),
            files('files'),
            js(),
            css()
        )
    );
}

function findTheme(name) {
    let result = null;

    if (!name) {
        return result;
    }

    json.themes.forEach(theme => {
        if (theme.name === name) {
            result = theme;
        }
    });

    return result;
}

function collect() {
    let tasks = [];

    for (let theme = findTheme(themeName); theme; theme = findTheme(theme.parent)) {
        theme.paths.reverse().forEach(path => {
            tasks.push(collectFrom(path));
        });
    }

    return series(...tasks.reverse());
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

function importJsModules() {
    function fn() {
        let dir = `${appName}/${themeName}/js`;

        return src(`temp/${dir}/scripts.js`, { base: `temp/${dir}`})
            .pipe(replace('/* @import_osm_modules */', function() {
                return json.modules.map(moduleName => {
                    return fs.existsSync(`temp/${dir}/${moduleName}/scripts.js`)
                        ? `import './${moduleName}/scripts.js';\n`
                        : '';
                }).join('');
            }))
            .pipe(dest(`temp/${dir}`));
    }
    return exports[fn.displayName = `importJsModules('${appName}', '${themeName}')`] = fn;
}

function importCssModules() {
    function fn() {
        let dir = `${appName}/${themeName}/css`;

        return src(`temp/${dir}/styles.css`, { base: `temp/${dir}`})
            .pipe(replace('/* @import_osm_modules */', function() {
                return json.modules.map(moduleName => {
                    return fs.existsSync(`temp/${dir}/${moduleName}/styles.css`)
                        ? `@import './${moduleName}/styles.css';\n`
                        : '';
                }).join('');
            }))
            .pipe(dest(`temp/${dir}`));
    }
    return exports[fn.displayName = `importCssModules('${appName}', '${themeName}')`] = fn;
}


function files(path) {
    function fn() {
        let dir = `${appName}/${themeName}/${path}`;

        let patterns = [`temp/${dir}/theme/**`];
        json.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return src(patterns, { base: `temp/${dir}`})
            .pipe(dest(`public/${dir}`));
    }
    return exports[fn.displayName = `files('${appName}', '${themeName}', '${path}')`] = fn;
}

function importModules(options) {
    return through.obj(function(file, _, cb) {
        if (file.isBuffer() &&
            file.contents.indexOf('//@import_osm_modules') !== -1)
        {
            let replacement = '';
            json.modules.forEach(moduleName => {
                if (fs.existsSync(options.pathPattern.replace(
                    '{moduleName}', moduleName)))
                {
                    replacement += options.importPattern.replace(
                        '{moduleName}', moduleName);
                }
            });

            file.contents = Buffer.from(file.contents.toString().replace(
                '//@import_osm_modules', replacement));
        }
        cb(null, file);
    });
}

function js() {
    function fn() {
        let dir = `${appName}/${themeName}/js`;

        let patterns = [`temp/${dir}/theme/**`, `temp/${dir}/scripts.js`];
        json.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return src(patterns, { base: `temp/${dir}`})
            .pipe(sourcemaps.init())
            .pipe(rollup({
                input: `temp/${dir}/scripts.js`,
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