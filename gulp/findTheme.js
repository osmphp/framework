module.exports = function findTheme(name) {
    let result = null;

    if (!name) {
        return result;
    }

    global.json.themes.forEach(theme => {
        if (theme.name === name) {
            result = theme;
        }
    });

    return result;
};
