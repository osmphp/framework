const findTheme = require('./findTheme');

module.exports = function themePaths(themeName, config) {
    let result = [];
    for (let theme = findTheme(themeName, config); theme;
        theme = findTheme(theme.parent, config))
    {
        theme.paths.reverse().forEach(path => {
            result.push(path);
        });
    }

    return result.reverse();
}