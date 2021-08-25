const fs = require("fs");

const task = require('./task');

module.exports = function save(appName, themeName, config) {
    return task(`save('${appName}', '${themeName}')`, function (cb) {
        fs.mkdirSync(`temp/${appName}/${themeName}`,
            {recursive: true});

        fs.writeFileSync(`temp/${appName}/${themeName}/config.json`,
            JSON.stringify(config));

        cb();
    });
}