export default class Merger {
    merge(target, source) {
        for (let i = 1; i < arguments.length; i++) {
            target = this._mergeFromSource(target, arguments[i]);
        }
        return target;
    }

    _mergeFromSource(target, source) {
        if (target instanceof Array) {
            return this._mergeIntoArray(target, source);
        }
        if (target instanceof Function) {
            return source;
        }
        if (target instanceof RegExp) {
            return source;
        }
        if (target instanceof Object) {
            return this._mergeIntoObject(target, source);
        }

        return source;
    }

    _mergeIntoObject(target, source) {
        if (!source) {
            return target;
        }

        for (let property in source) {
            if (!source.hasOwnProperty(property)) continue;

            target[property] = this._mergeFromSource(target[property], source[property]);
        }

        return target;
    }

    _mergeIntoArray (target, source) {
        if (!source) {
            return target;
        }

        target = target.concat(source);

        return target;
    }
};
