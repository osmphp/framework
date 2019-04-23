export default function debounceForAnimationFrame(callback, timeout) {
    let calledInCurrentTick = false;
    let debouncing = false;

    return function() {
        function onNextTick() {
            if (!calledInCurrentTick) {
                callback.apply(this, arguments);
                debouncing = false;
            }
            else {
                calledInCurrentTick = false;
                setTimeout(onNextTick, timeout);
            }
        }

        calledInCurrentTick = true;
        if (debouncing) {
            return;
        }

        debouncing = true;
        setTimeout(onNextTick, timeout);
    }
};