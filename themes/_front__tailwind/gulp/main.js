const build = require('./build');
const watch = require('./watch');

global.tasks = {};
exports.default = build();
exports.watch = watch();
Object.assign(exports, global.tasks);