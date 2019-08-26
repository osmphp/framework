import Controller from "Osm_Framework_Js/Controller";
import dialogs from 'Osm_Ui_Dialogs/vars/dialogs';
import osm_t from "Osm_Framework_Js/osm_t";

export default class DialogsPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'click #exception_button': 'onExceptionBtnClick',
            'click #yes_no_button': 'onYesNoBtnClick'
        });
    }

    onExceptionBtnClick() {
        dialogs.show('exception', {
            width: 1900,
            height: 700,
            message: 'Exception message',
            stack_trace: 'Exception stack trace'
        }).then(result => {
            console.log(result);
        });
    }

    onYesNoBtnClick() {
        dialogs.show('yes_no', {
            width: 500,
            height: 150,
            message: osm_t("Do you really want to delete this item?")
        }).then(result => {
            console.log(result);
        });
    }
};