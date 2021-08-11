const {series, parallel} = require('gulp');

const buildApp = require('./buildApp');
const watchApp = require('./watchApp');
const buildTheme = require('./buildTheme');
const watchTheme = require('./watchTheme');

global.tasks = {};

let buildTasks = [];
let watchTasks = [];

for (let appName in global.config) {
    if (!global.config.hasOwnProperty(appName)) continue;

    let buildAppTasks = [buildApp(appName)];
    let watchAppTasks = [watchApp(appName)];

    global.config[appName].forEach(function(themeName) {
        buildAppTasks.push(buildTheme(appName, themeName));
        watchAppTasks.push(watchTheme(appName, themeName));
    });

    buildTasks.push(global.tasks[`build:${appName}`] = series(...buildAppTasks));
    watchTasks.push(global.tasks[`watch:${appName}`] = series(
        buildApp(appName),
        parallel(...watchAppTasks))
    );
}

global.tasks.default = parallel(...buildTasks);
global.tasks.watch = parallel(...watchTasks);

Object.assign(exports, global.tasks);