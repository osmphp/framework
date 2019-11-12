const {watch} = require('gulp');
const execSync = require('child_process').execSync;
const fs = require('fs');
const dotenv = require('dotenv');



function createWatchTask(env) {
    return function() {
        watchTask(env);
    };
}

function watchTask(env) {
    let envParameter = env ? ' --env=' + env: '';
    if (!env) {
        env = dotenv.parse(fs.readFileSync('.env')).APP_ENV || 'production';
    }
    let tempPath = 'temp/' + env + '/notifications';

    execSync('php run config:gulp' + envParameter, {stdio: 'inherit'});
    let patterns = JSON.parse(fs.readFileSync('temp/' + env + '/gulp.json', 'utf8'));

    //['data/**/*.*', '/home/osmdocs.vm/**/*.*'];

    let paths = [];
    let watcher = watch(patterns, function process_file_changes (cb) {
        let input = paths;
        paths = [];

        if (!input.length) {
            return;
        }

        if (input.length < 10) {
            execSync('php run notify:data-changed' + envParameter + ' ' +
                input.map(path => '"' + path + '"').join(" "),
                {stdio: 'inherit'});
        }
        else {
            let tempFile = tempPath + '/' + (new Date).getTime() + '.txt';

            if (!fs.existsSync(tempPath)) {
                fs.mkdirSync(tempPath, {recursive: true});
            }
            fs.writeFileSync(tempFile, input.join("\n"));

            execSync('php run notify:data-changed' + envParameter +
                ' --filelist=' + tempFile,
                {stdio: 'inherit'});

            fs.unlinkSync(tempFile);
        }
        console.log(input.length + ' file(s) processed.');
        cb();
    });

    function addPath(path) {
        if (paths.indexOf(path) == -1) {
            paths.push(path);
        }
    }

    watcher.on('change', addPath);
    watcher.on('add', addPath);
    watcher.on('unlink', addPath);
}

exports.watch = createWatchTask();
exports.testing_watch = createWatchTask('testing');