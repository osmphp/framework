import CancelModalDialog from "./CancelModalDialog";

export default class YesNoModalDialog extends CancelModalDialog {
    get events() {
        return Object.assign({}, super.events, {
            'click &___footer__yes': 'onYesClick'

        });
    }

    onYesClick() {
        this.resolve(true);
    }
};