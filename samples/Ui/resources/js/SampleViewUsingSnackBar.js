import Controller from "Manadev_Framework_Js/Controller";
import snackBars from 'Manadev_Ui_SnackBars/vars/snackBars';

export default class SampleViewUsingSnackBar extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click &__normal': 'onNormalClick',
            'click &__modal': 'onModalClick',
            'click &__exception': 'onExceptionClick'
        });
    }

    onNormalClick() {
        snackBars.showMessage('Here is sample snack message. It hides automatically after 5 seconds.');
    }

    onModalClick() {
        snackBars.modal('test', {
            message: 'Here is sample snack message. Normally, snack bar hides automatically after 5 ' +
                'seconds. However, this one is "modal", it hides after some JS code explicitly tells it to close. ' +
                'To close this snack bar, press close button.'
        });
    }

    onExceptionClick() {
        try {
            throw new Error('Some error');
        }
        catch (e) {
            snackBars.show('exception', {
                message: e.message,
                stack_trace: e.stack
            });
        }
    }
};