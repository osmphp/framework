export default function mix(class_, mixin) {
    function findPropertyDescriptor(class_, property) {
        for (let c = class_; c.prototype; c = Object.getPrototypeOf(c)) {
            let descriptor = Object.getOwnPropertyDescriptor(c.prototype, property);

            if (descriptor) {
                return descriptor;
            }
        }

        return null;
    }

    Object.getOwnPropertyNames(mixin.prototype).forEach(property => {
        let descriptor = null;

        if (property == 'constructor') {
            return;
        }

        if (property.startsWith('around_get_')) {
            let getter = property.substr('around_get_'.length);
            let definingClass = class_;

            for (; definingClass.prototype; definingClass = Object.getPrototypeOf(definingClass)) {
                if (descriptor = Object.getOwnPropertyDescriptor(definingClass.prototype, getter)) {
                    break;
                }
            }

            if (!descriptor) {
                return;
            }

            let proceed = descriptor.get;
            Object.defineProperty(definingClass.prototype, getter, {
                get () {
                    let args = Array.prototype.slice.call(arguments);
                    args.unshift(proceed.bind(this));
                    return mixin.prototype[property].apply(this, args);
                }
            });

            return;
        }

        if (property.startsWith('around_')) {
            let method = property.substr('around_'.length);

            if (!class_.prototype[method]) {
                return;
            }

            let proceed = class_.prototype[method];
            class_.prototype[method] = function() {
                let args = Array.prototype.slice.call(arguments);
                args.unshift(proceed.bind(this));
                return mixin.prototype[property].apply(this, args);
            };

            return;
        }

        if (!(descriptor = Object.getOwnPropertyDescriptor(mixin.prototype, property))) {
            class_.prototype[property] = mixin.prototype[property];
            return;
        }

        if (descriptor.get) {
            Object.defineProperty(class_.prototype, property, {
                get() {
                    return descriptor.get.apply(this);
                }
            });
            return;
        }

        class_.prototype[property] = mixin.prototype[property];
    });
};

