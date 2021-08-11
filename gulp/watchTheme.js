const {watch} = require("gulp");
const {exec, spawn} = require("child_process");
const fs = require("fs");

const osmt = require('./osmt');
const task = require('./task');

module.exports = function watchTheme(appName, themeName) {
    return task(`watchTheme('${appName}', '${themeName}')`, function () {
        let p;

        watch([`generated/${appName}/app.ser`], () => {
            return exec(`php ${osmt} config:gulp --app=${appName}`,
                (error, stdout) => {
                    if (error) {
                        return;
                    }

                    let json = fs.readFileSync(
                        `temp/${appName}/${themeName}/config.json`);

                    if (json != stdout.toString()) {
                        fs.writeFileSync(
                            `temp/${appName}/${themeName}/config.json`,
                            stdout.toString());
                        spawnThemeGulpfile();
                    }
                });
        });

        spawnThemeGulpfile();

        function spawnThemeGulpfile() {
            // kill previous spawned process
            if (p) {
                p.kill();
            }

            let json = fs.readFileSync(
                `temp/${appName}/${themeName}/config.json`);

            p = spawn('node', [process.mainModule.filename, 'watch',
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
        }
    });
}