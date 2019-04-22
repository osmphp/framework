import templates from 'Manadev_Framework_Js/vars/templates';
import macaw from "Manadev_Framework_Js/vars/macaw";
import CancelModalDialog from "./CancelModalDialog";
import YesNoModalDialog from "./YesNoModalDialog";

templates.add('dialog__exception', {route: 'GET /dialogs/exception'});
templates.add('dialog__yes_no', {route: 'GET /dialogs/yes-no'});

macaw.controller('.modal-dialog.-exception', CancelModalDialog);
macaw.controller('.modal-dialog.-yes-no', YesNoModalDialog);