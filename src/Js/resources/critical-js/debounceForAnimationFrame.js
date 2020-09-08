export default function debounceForAnimationFrame(callback) {
    let calledInCurrentAnimationFrame = false;
    let debouncing = false;

    return function() {
        function onNextAnimationFrame() {
            if (!calledInCurrentAnimationFrame) {
                callback.apply(this, arguments);
                debouncing = false;
            }
            else {
                calledInCurrentAnimationFrame = false;
                window.requestAnimationFrame(onNextAnimationFrame);
            }
        }

        calledInCurrentAnimationFrame = true;
        if (debouncing) {
            return;
        }

        debouncing = true;
        window.requestAnimationFrame(onNextAnimationFrame);
    }
};