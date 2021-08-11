const {watch, series, parallel, src, dest} = require('gulp');
const fs = require('fs');
const del = require('del');
const {exec, spawn} = require('child_process');

global.tasks = {};

const osmc = fs.existsSync('vendor/osmphp/core/bin/compile.php')
    ? 'vendor/osmphp/core/bin/compile.php'
    : 'bin/compile.php';
const osmt = fs.existsSync('vendor/osmphp/framework/bin/tools.php')
    ? 'vendor/osmphp/framework/bin/tools.php'
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
        watch(['src/**', 'samples/**', 'tools/**', 'composer.lock',
            '.env.*', 'settings.*.php'],
            buildApp(appName));
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
    return global.tasks[fn.displayName = `save('${appName}', '${themeName}')`] = fn;
}

function call(appName, themeName) {
    function fn() {
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
    }

    return global.tasks[fn.displayName = `call('${appName}', '${themeName}')`] = fn;
}

function watchTheme(appName, themeName) {
    function fn() {
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
    }
    return global.tasks[fn.displayName = `watchTheme('${appName}', '${themeName}')`] = fn;
}

//region Gulp tasks
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
//endregion

Object.assign(exports, global.tasks);