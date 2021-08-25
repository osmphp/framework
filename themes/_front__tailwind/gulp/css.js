const {src, dest} = require("gulp");
const if_ = require("gulp-if");
const sourcemaps = require("gulp-sourcemaps");
const csso = require("postcss-csso");
const postcss = require("gulp-postcss");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');

module.exports = function css() {
    return task(`css('${appName}', '${themeName}')`, function () {
        const plugins = [
            require('postcss-import'),
            require('tailwindcss')({config: __dirname + '/../tailwind.config.js'}),
            require('autoprefixer')
        ];
        if (config.production) {
            plugins.push(csso);
        }

        return src(`temp/${appName}/${themeName}/css/styles.css`)
            .pipe(if_(!config.production, sourcemaps.init()))
            .pipe(postcss(plugins))
            .pipe(if_(!config.production, sourcemaps.write('.')))
            .pipe(dest(`public/${appName}/${themeName}`));
    });
}