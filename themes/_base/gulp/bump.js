const through = require('through2');
const fs = require("fs");

const appName = require('./appName');
const themeName = require('./themeName');
const task = require('./task');

function version() {
    let text = "";
    let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 0; i < 8; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function write(version) {
    fs.mkdirSync(`public/${appName}/${themeName}`,
        {recursive: true});
    fs.writeFileSync(`public/${appName}/${themeName}/version.txt`, version);
}

let v;

module.exports.once = function() {
    return task(`bump('${appName}', '${themeName}')`, function (cb) {
        write(v = version());
        cb();
    });
}

module.exports.after = function() {
    return through.obj(function (file, _, cb) {
        cb();
    }, function(cb) {
        if (!v) {
            write(version());
        }
        cb();
    })
}