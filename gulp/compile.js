const {spawn} = require("child_process");

const osmc = require('./osmc');
const task = require('./task');

module.exports = function compile(appName) {
    return task(`compile('${appName}')`, function () {
        return spawn('php', [osmc, appName], {stdio: 'inherit'});
    });
}
