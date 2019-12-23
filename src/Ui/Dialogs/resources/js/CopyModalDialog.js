import CancelModalDialog from "./CancelModalDialog";

export default class CopyModalDialog extends CancelModalDialog {
    get events() {
        return Object.assign({}, super.events, {
            'click &___footer__copy': 'onCopyClick'
        });
    }

    get container() {
        return document.getElementById('modal_dialog_container');
    }

    onCopyClick() {
        // select element contents
        if (document.selection) {
            let range = document.body.createTextRange();
            range.moveToElementText(this.container);
            range.select();
        } else if (window.getSelection) {
            let range = document.createRange();
            range.selectNodeContents(this.container);
            getSelection().removeAllRanges();
            getSelection().addRange(range);
        }

        document.execCommand('copy');

        this.resolve(true);
    }
};