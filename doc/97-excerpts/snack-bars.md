# Snack Bars #

Snack bar is a popup message at the bottom of the screen:

**TODO**. Image

See also: [Material Design: Snackbars](https://material.io/design/components/snackbars.html)

## Messages ##

To show snack bar message, call `snackBars.showMessage()`:

    import snackBars from 'Manadev_Ui_SnackBars/vars/snackBars';

    snackBars.showMessage('Hi');

The message automatically closes after 5 seconds. You can change this timeout in `close_snack_bars_after` setting.  

**TODO**. Explain `import`

**TODO**. Explain settings.

## Visualizing AJAX Requests ##

Visualize AJAX requests to user using snack bars as shown below:

    import snackBars from 'Manadev_Ui_SnackBars/vars/snackBars';
    import ajax from 'Manadev_Framework_Js/ajax';

    // modal message won't hide automatically
    let snackBar = snackBars.modalMessage('Processing ...');

    ajax('POST /some-route', { payload: { /* ... */} })
        .then(payload => {
            // show a message if user debugged on server and stopped debugging session
            // for some reason, empty HTTP 200 response is returned after stopping debugging session, 
            // so this case should be handled in "success" case
            if (snackBars.handleEmptyPayload(payload)) return;

            // handle legitimate success response here
        })
        .catch(xhr => {
            // handle legitimate error response here and "return;"

            // show server error message in separate snackbar. This one will close automatically 
            // after timeout 
            snackBars.handleServerError(xhr);
        })
        .finally(() => {
            // in any case, close snack bar showing 'Processing ...'
            snackBar.close();
        });
 
**TODO**. Explain AJAX

**TODO**. Explain promises

**TODO**. Showing custom messages (adding buttons).