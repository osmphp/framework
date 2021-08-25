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
const if_ = require('gulp-if');
const {uglify} = require('rollup-plugin-uglify');
const csso = require('postcss-csso');

const json = require('../config.json');
const appName = process.env.GULP_APP;
const themeName = process.env.GULP_THEME;

const build = require('./build');

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

        let plugins = [commonjs(), nodeResolve()];
        if (json.production) {
            plugins.push(uglify());
        }

        return src(patterns, { base: `temp/${dir}`})
            .pipe(if_(!json.production, sourcemaps.init()))
            .pipe(rollup({
                input: `temp/${dir}/scripts.js`,
                output: {
                    //name: 'osm_app',
                    file: 'scripts.js',
                    format: 'iife',
                    sourcemap: !json.production,
                },
                plugins: plugins,
            }))
            .pipe(if_(!json.production, sourcemaps.write('.')))
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
        const plugins = [
            require('postcss-import'),
            require('tailwindcss')({config: __dirname + '/../tailwind.config.js'}),
            require('autoprefixer')
        ];
        if (json.production) {
            plugins.push(csso);
        }

        return src(`temp/${appName}/${themeName}/css/styles.css`)
            .pipe(if_(!json.production, sourcemaps.init()))
            .pipe(postcss(plugins))
            .pipe(if_(!json.production, sourcemaps.write('.')))
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

global.tasks = {};
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
Object.assign(exports, global.tasks);