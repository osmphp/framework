const {src, dest} = require("gulp");
const replace = require("gulp-replace");
const fs = require("fs");

const appName = require('./appName');
const themeName = require('./themeName');
const config = require('./config');
const task = require('./task');

module.exports = function importJsModules() {
    return task(`importJsModules('${appName}', '${themeName}')`, function () {
        let dir = `${appName}/${themeName}/js`;

        return src(`temp/${dir}/scripts.js`, {base: `temp/${dir}`})
            .pipe(replace('/* @import_osm_modules */', function () {
                let directive = config.modules.map(moduleName => {
                    return fs.existsSync(`temp/${dir}/${moduleName}/scripts.js`)
                        ? `import './${moduleName}/scripts.js';\n`
                        : '';
                }).join('');

                if (fs.existsSync(`temp/${dir}/theme/scripts.js`)) {
                    directive += `import './theme/scripts.js';\n`;
                }

                return directive;
            }))
            .pipe(dest(`temp/${dir}`));
    });
}