const fs = require("fs");
const {spawn} = require("child_process");

const task = require('./task');

module.exports = function call(appName, themeName, config) {
    return task(`call('${appName}', '${themeName}')`, function fn() {
        return spawn('node', [process.mainModule.filename,
                '-f', `temp/${appName}/${themeName}/gulp/main.js`,
                '--cwd', process.cwd()],
            {
                stdio: 'inherit',
                env: Object.assign({}, process.env, {
                    GULP_APP: appName,
                    GULP_THEME: themeName,
                    NODE_ENV: config.production ? 'production' : 'development',
                })
            }
        );
    });
}