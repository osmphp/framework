const findTheme = require('./findTheme');

module.exports = function themePaths(themeName) {
    let result = [];
    for (let theme = findTheme(themeName); theme; theme = findTheme(theme.parent)) {
        theme.paths.reverse().forEach(path => {
            result.push(path);
        });
    }

    return result.reverse();
}