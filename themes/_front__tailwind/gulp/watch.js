const {parallel} = require("gulp");

const watchFiles = require("./watchFiles");
const watchJs = require("./watchJs");
const watchCss = require("./watchCss");

module.exports = parallel(
    watchFiles('images'),
    watchFiles('fonts'),
    watchFiles('files'),
    watchJs(),
    watchCss(),
);