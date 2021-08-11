const fs = require("fs");
const {spawn} = require("child_process");

const task = require('./task');

module.exports = function call(appName, themeName) {
    return task(`call('${appName}', '${themeName}')`, function fn() {
        let json = JSON.parse(fs.readFileSync(
            `temp/${appName}/${themeName}/config.json`).toString());

        return spawn('node', [process.mainModule.filename,
                '-f', `temp/${appName}/${themeName}/gulpfile.js`,
                '--cwd', process.cwd()],
            {
                stdio: 'inherit',
                env: Object.assign({}, process.env, {
                    GULP_APP: appName,
                    GULP_THEME: themeName,
                    NODE_ENV: json.production ? 'production' : 'development',
                })
            }
        );
    });
}