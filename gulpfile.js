const {watch, series, parallel, src, dest} = require('gulp');
const fs = require('fs');
const del = require('del');
const {exec, spawn} = require('child_process');

const config = {
    'Osm_Tools': [],
    'Osm_Framework_Samples': ['_front__tailwind']
}

const osmc = fs.existsSync('vendor/osmphp/core/bin/compile.php')
    ? 'vendor/osmphp/core/bin/compile.php'
    : 'bin/compile.php';
const osmt = fs.existsSync('vendor/osmphp/framework/bin/tools.php')
    ? 'vendor/osmphp/core/bin/tools.php'
    : 'bin/tools.php';

function buildApp(appName) {
    return series(
        compile(appName),
        refresh(appName),
    );
}

function compile(appName) {
    function fn() {
        return spawn('php', [osmc, appName], {stdio: 'inherit'});
    }
    return exports[fn.displayName = `compile('${appName}')`] = fn;
}

function refresh(appName) {
    function fn() {
        return spawn('php', [osmt, 'refresh', `--app=${appName}`], {stdio: 'inherit'});
    }

    return exports[fn.displayName = `refresh('${appName}')`] = fn;
}

function watchApp(appName) {
    function fn() {
        watch(['src/**', 'samples/**', 'composer.lock'], buildApp(appName));
    }
    return exports[fn.displayName = `watchApp('${appName}')`] = fn;
}

function buildTheme(appName, themeName) {
    return series(
        clear(appName, themeName),
        save(appName, themeName),
        call(appName, themeName)
    );
}

function clear(appName, themeName) {
    function fn() {
        return del(`temp/${appName}/${themeName}/**`);
    }
    return exports[fn.displayName = `clearTemp('${appName}', '${themeName}')`] = fn;
}

function save(appName, themeName) {
    function fn() {
        return exec(`php ${osmt} config:gulp --app=${appName}`,
            (error, stdout) => {
                fs.mkdirSync(`temp/${appName}/${themeName}`,
                    {recursive: true});

                fs.writeFileSync(`temp/${appName}/${themeName}/config.json`,
                    stdout.toString());

                let json = JSON.parse(stdout.toString());
                json.themes.forEach(theme => {
                    if (theme.name === themeName) {
                        fs.copyFileSync(theme.gulpfile,
                            `temp/${appName}/${themeName}/gulpfile.js`);
                    }
                });
            }
        );
    }
    return exports[fn.displayName = `save('${appName}', '${themeName}')`] = fn;
}

function call(appName, themeName) {
    function fn() {
        return spawn('node', [process.mainModule.filename,
            '-f', `temp/${appName}/${themeName}/gulpfile.js`,
            '--cwd', process.cwd()],
            {
                stdio: 'inherit',
                env: Object.assign({}, process.env, {
                    GULP_APP: appName,
                    GULP_THEME: themeName
                })
            }
        );
    }

    return exports[fn.displayName = `call('${appName}', '${themeName}')`] = fn;
}

function watchTheme(appName, themeName) {
    function fn(cb) {
        cb();
    }
    return exports[fn.displayName = `watchTheme('${appName}', '${themeName}')`] = fn;
}

//region Gulp tasks
let buildTasks = [];
let watchTasks = [];

for (let appName in config) {
    if (!config.hasOwnProperty(appName)) continue;

    let buildAppTasks = [buildApp(appName)];
    let watchAppTasks = [watchApp(appName)];

    config[appName].forEach(function(themeName) {
        buildAppTasks.push(buildTheme(appName, themeName));
        watchAppTasks.push(watchTheme(appName, themeName));
    });

    buildTasks.push(exports[`build:${appName}`] = series(...buildAppTasks));
    watchTasks.push(exports[`watch:${appName}`] = parallel(...watchAppTasks));
}

exports.default = parallel(...buildTasks);
exports.watch = parallel(...watchTasks);
//endregion