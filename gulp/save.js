const {exec} = require("child_process");
const fs = require("fs");

const osmt = require('./osmt');
const task = require('./task');

module.exports = function save(appName, themeName) {
    return task(`save('${appName}', '${themeName}')`, function () {
        return exec(`php ${osmt} config:gulp --app=${appName}`,
            (error, stdout) => {
                fs.mkdirSync(`temp/${appName}/${themeName}`,
                    {recursive: true});

                fs.writeFileSync(`temp/${appName}/${themeName}/config.json`,
                    stdout.toString());

                global.json = JSON.parse(stdout.toString());
            }
        );
    });
}