module.exports = function findTheme(name, config) {
    let result = null;

    if (!name) {
        return result;
    }

    config.themes.forEach(theme => {
        if (theme.name === name) {
            result = theme;
        }
    });

    return result;
};
