const {spawn} = require("child_process");

const osmt = require('./osmt');
const task = require('./task');

module.exports = function refresh(appName) {
    return task(`refresh('${appName}')`, function () {
        return spawn('php', [osmt, 'refresh', `--app=${appName}`], {stdio: 'inherit'});
    });
}