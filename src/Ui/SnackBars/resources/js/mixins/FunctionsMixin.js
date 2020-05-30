import snackBars from '../vars/snackBars';

export default class FunctionsMixin {
    around_ajax(proceed, route, options) {
        let ajax = proceed(route, options);

        if (!options.snackbar_message) {
            return ajax;
        }

        let snackBar = snackBars.modalMessage(options.snackbar_message);

        return ajax
            .catch(response => {
                if (response instanceof Error) {
                    snackBars.show('exception', {
                        message: response.message,
                        stack_trace: response.stack
                    });
                    return Promise.reject();
                }

                if (response.headers.get("content-type") == 'application/json') {
                    return response.json().then(json => {
                        json.message = response.headers.get("status-text");
                        return Promise.reject(json);
                    });
                }

                if (!response.headers.has("status-text")) {
                    console.log('Empty status text received: ', response);
                    return Promise.reject();
                }

                let statusText = response.headers.get("status-text");
                response.text().then(text => {
                    if (!text.length) {
                        snackBars.showMessage(statusText);
                    }
                    else {
                        snackBars.show('exception', {
                            message: statusText,
                            stack_trace: text
                        });
                    }
                });
                return Promise.reject();
            })
            .finally(() => {
                snackBar.close();
            });
    }
};