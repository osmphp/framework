import Controller from "Osm_Framework_Js/Controller";
import macaw from "Osm_Framework_Js/vars/macaw";
import PopupMenu from "Osm_Ui_Menus/PopupMenu/Menu";
import snackBars from "Osm_Ui_SnackBars/vars/snackBars";
import formatString from "Osm_Framework_Js/formatString";

export default class UploadsPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'button:click #popup_button': 'onButtonClick',
            'button:upload #upload': 'onUpload',
            'menuitem:upload:upload #popup_menu': 'onUpload',
            'menuitem:upload:upload #heading__menu': 'onUpload',
        });
    }

    get menu() {
        return macaw.get('#popup_menu', PopupMenu);
    }

    get uploaded_images_element() {
        return document.getElementById('uploaded_images');
    }

    onButtonClick(e) {
        this.menu.open(e.currentTarget);
    }

    onUpload(e) {
        snackBars.showMessage(formatString(
        ":count image(s) successfully uploaded.", {
                count: e.detail.files.length
            }));

        console.log(e.detail.files);

        e.detail.files.forEach(file => {
            let element = (new DOMParser()).parseFromString(
                file.html, 'text/html').body;
            this.uploaded_images_element.append(element);
        });
    }
};