const {watch, series, parallel, src, dest} = require('gulp');
const watchSrc = require('gulp-watch');
const fs = require('fs');
const {rollup} = require('gulp-rollup-2');
const {nodeResolve} = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');
const del = require('del');
const through = require('through2');
const replace = require('gulp-replace');
const {relative} = require('path');
const print = require('gulp-print').default;
const json = require('./config.json');

const appName = process.env.GULP_APP;
const themeName = process.env.GULP_THEME;

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

function themePaths() {
    let result = [];
    for (let theme = findTheme(themeName); theme; theme = findTheme(theme.parent)) {
        theme.paths.reverse().forEach(path => {
            result.push(path);
        });
    }

    return result.reverse();
}

function collect() {
    return series(...themePaths().map(path => collectFrom(path)));
}

function collectFrom(sourcePath) {
    function fn() {
        return src(`${sourcePath}/**`)
            .pipe(dest(`temp/${appName}/${themeName}`));
    }
    return exports[fn.displayName = `collectFrom('${appName}', '${themeName}', '${sourcePath}')`] = fn;
}

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

function watchFiles(path) {
    function fn() {
        let dir = `${appName}/${themeName}/${path}`;

        let patterns = [`temp/${dir}/theme/**`];
        json.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return watchSrc(patterns, {base: `temp/${dir}`})
            .pipe(print(filepath => `files(${filepath})`))
            .pipe(dest(`public/${dir}`));
    }
    return exports[fn.displayName = `watchFiles('${appName}', '${themeName}', '${path}')`] = fn;
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

function watchJs() {
    function fn() {
        let dir = `${appName}/${themeName}/js`;

        let patterns = [`temp/${dir}/theme/**`, `temp/${dir}/scripts.js`];
        json.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return watch(patterns, js());
    }
    return exports[fn.displayName = `watchJs('${appName}', '${themeName}')`] = fn;
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

function watchCss() {
    function fn() {
        let dir = `${appName}/${themeName}/css`;

        let patterns = [`temp/${dir}/theme/**`, `temp/${dir}/styles.css`];
        json.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        return watch(patterns, css());
    }
    return exports[fn.displayName = `watchCss('${appName}', '${themeName}')`] = fn;
}

function build() {
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

exports.default = build();

exports.watch = series(
    build(),
    parallel(
        watchCollect(),
        watchFiles('images'),
        watchFiles('fonts'),
        watchFiles('files'),
        watchJs(),
        watchCss(),
    ),
);