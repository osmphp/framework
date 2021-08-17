const {src, dest} = require("gulp");

const task = require('./task');

module.exports = function collectFrom(appName, themeName, sourcePath) {
    return task(`collectFrom('${appName}', '${themeName}', '${sourcePath}')`,
        function fn() {
            return src(`${sourcePath}/**`)
                .pipe(dest(`temp/${appName}/${themeName}`));
        }
    );
};
