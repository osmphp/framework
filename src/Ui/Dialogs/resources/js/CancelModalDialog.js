import ModalDialog from "./ModalDialog";

export default class CancelModalDialog extends ModalDialog {
    get events() {
        return Object.assign({}, super.events, {
            'click &___footer__cancel': 'onCancelClick'

        });
    }

    onCancelClick() {
        this.resolve();
    }
};