import Action from "Manadev_Framework_Js/Action";
import $ from 'jquery';

export default class MouseHandling extends Action {
    onDocumentClick(e) {
        if (!this.model.opened) {
            return;
        }

        // when anchor element is clicked, and menu is being opened this document click handler is invoked too and
        // without this guard menu is closed without even shown to user
        if (this.model.opening) {
            return;
        }

        // ignore clicks in any popup menu
        if ($(e.target).parents('.popup-menu').length) {
            return;
        }

        this.controller.close();
    }
};