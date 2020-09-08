export default function callOncePerAnimationFrame(callback) {
    let calledInCurrentAnimationFrame = false;

    return function() {
        if (calledInCurrentAnimationFrame) {
            return;
        }
        calledInCurrentAnimationFrame = true;
        callback.apply(this, arguments);

        window.requestAnimationFrame(() => {
            calledInCurrentAnimationFrame = false;
        });
    }
};