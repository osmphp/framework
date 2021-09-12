const {src, dest} = require("gulp");
const commonjs = require("@rollup/plugin-commonjs");
const {nodeResolve} = require("@rollup/plugin-node-resolve");
const {terser} = require("rollup-plugin-terser");
const if_ = require("gulp-if");
const sourcemaps = require("gulp-sourcemaps");
const {rollup} = require("gulp-rollup-2");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');
const bump = require("./bump");

module.exports = function js() {
    return task(`js('${appName}', '${themeName}')`, function () {
        let dir = `${appName}/${themeName}/js`;

        let patterns = [`temp/${dir}/theme/**`, `temp/${dir}/scripts.js`];
        config.modules.forEach(moduleName => {
            patterns.push(`temp/${dir}/${moduleName}/**`);
        });

        let plugins = [commonjs(), nodeResolve()];
        if (config.production) {
            plugins.push(terser());
        }

        return src(patterns, {base: `temp/${dir}`})
            .pipe(if_(!config.production, sourcemaps.init()))
            .pipe(rollup({
                input: `temp/${dir}/scripts.js`,
                output: {
                    file: 'scripts.js',
                    format: 'iife',
                    sourcemap: !config.production,
                },
                plugins: plugins,
            }))
            .pipe(if_(!config.production, sourcemaps.write('.')))
            .pipe(dest(`public/${appName}/${themeName}`))
            .pipe(bump.after());
    });
}