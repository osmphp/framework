import snackBars from '../vars/snackBars';

export default class FunctionsMixin {
    around_ajax(proceed, route, options) {
        let ajax = proceed(route, options);

        if (!options.snackbar_message) {
            return ajax;
        }

        let snackBar = snackBars.modalMessage(options.snackbar_message);
        return ajax
            .then(payload => {
                if (payload === undefined) return payload; // response fully handled by previous then()

                if (snackBars.handleEmptyPayload(payload)) {
                    // subsequent then() will know than response is fully handled
                    return undefined;
                }

                return payload;
            })
            .catch(xhr => {
                if (snackBars.handleServerError(xhr)) {
                    // subsequent then() will know than response is fully handled
                    return undefined;
                }

                // pass error response to subsequent catch
                throw xhr;
            })
            .finally(() => {
                snackBar.close();
            });
    }
};