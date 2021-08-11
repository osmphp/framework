const fs = require("fs");

module.exports = fs.existsSync('vendor/osmphp/framework/bin/tools.php')
    ? 'vendor/osmphp/framework/bin/tools.php'
    : 'bin/tools.php';