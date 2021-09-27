const {watch} = require("gulp");
const buildApp = require("./buildApp");
const task = require('./task');

module.exports = function watchApp(appName) {
    return task(`watchApp('${appName}')`, function () {
        watch(['src/**', 'samples/**', 'tools/**', 'composer.lock',
                '.env.*', 'settings.php', 'settings.*.php', 'packages/**',
                'data/**'],
            buildApp(appName));
    });
}
