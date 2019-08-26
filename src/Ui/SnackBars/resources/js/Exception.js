import Controller from "Osm_Framework_Js/Controller";
import dialogs from 'Osm_Ui_Dialogs/vars/dialogs';

export default class Exception extends Controller {
    get events() {
        let result = super.events;
        result['click #' + this.element.id + '__stack-trace'] = 'onStackTraceClick';
        return result;
    }

    onAttach() {
        super.onAttach();
        document.getElementById(this.element.id + '__stack-trace').focus();
    }

    onStackTraceClick() {
        this.model.handle.close();
        dialogs.show('exception', {
            width: 1900,
            height: 700,
            message: this.model.handle.variables.message,
            stack_trace: this.model.handle.variables.stack_trace
        });
    }
};