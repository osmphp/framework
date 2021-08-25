const {src, dest} = require("gulp");
const replace = require("gulp-replace");
const fs = require("fs");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');

module.exports = function importCssModules() {
    return task(`importCssModules('${appName}', '${themeName}')`, function () {
        let dir = `${appName}/${themeName}/css`;

        return src(`temp/${dir}/styles.css`, {base: `temp/${dir}`})
            .pipe(replace('/* @import_osm_modules */', function () {
                return config.modules.map(moduleName => {
                    return fs.existsSync(`temp/${dir}/${moduleName}/styles.css`)
                        ? `@import './${moduleName}/styles.css';\n`
                        : '';
                }).join('');
            }))
            .pipe(dest(`temp/${dir}`));
    });
}