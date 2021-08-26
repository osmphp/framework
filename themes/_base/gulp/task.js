module.exports = function task(name, fn) {
    return global.tasks[fn.displayName = name] = fn;
}