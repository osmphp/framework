import templates from 'Manadev_Framework_Js/vars/templates';
import merge from 'Manadev_Framework_Js/merge';
import SnackBar from './SnackBar';
import ExceptionViewModel from "./ExceptionViewModel";
import macaw from "Manadev_Framework_Js/vars/macaw";
import Exception from "./Exception";
import snackBars from './vars/snackBars';
import mix from 'Manadev_Framework_Js/mix';
import Functions from "Manadev_Framework_Js/Functions";
import FunctionsMixin from "./mixins/FunctionsMixin";

mix(Functions, FunctionsMixin);

templates.add('snack-bar__message', {route: 'GET /snack-bars/message'});
templates.add('snack-bar__exception', {route: 'GET /snack-bars/exception'});

merge(window, {
    Manadev_Ui_SnackBars: { SnackBar, Exception: ExceptionViewModel }
});

macaw.controller(Manadev_Ui_SnackBars.Exception, Exception);

snackBars.restoreLastingMessage();
