import Field from './Field';
import macaw from "Osm_Framework_Js/vars/macaw";
import MenuBar from "Osm_Ui_Menus/MenuBar/Menu";
import templates from 'Osm_Framework_Js/vars/templates';
import Mustache from 'mustache';

export default class ImageField extends Field {
    get events() {
        return Object.assign({}, super.events, {
            'menuitem:upload:add &___menu': 'onUpload',
            'menuitem:upload:replace &___menu': 'onUpload',
            'menuitem:uploading:add &___menu': 'onUploading',
            'menuitem:uploading:replace &___menu': 'onUploading',
            'menuitem:command:clear &___menu': 'onClear',
        });
    }

    get image_element() {
        return this.element.querySelector('.form-section__image');
    }

    get menu() {
        return macaw.get(document.getElementById(
            this.getAliasedId('&___menu')), MenuBar);
    }

    onUploading(e) {
        e.detail.query = {
            width: this.model.width,
            height: this.model.height,
        };

        if (this.model.path) {
            e.detail.query.path = this.model.path;
        }
    }

    onUpload(e) {
        let file = e.detail.files[0];

        this.model.value = file.uid;
        this.model.filename = file.filename;

        this.image_element.parentNode.innerHTML = file.html;
        macaw.afterInserted(this.image_element);

        this.menu.getItem('add').hidden = true;
        this.menu.getItem('replace').hidden = false;
        this.menu.getItem('clear').hidden = false;
    }

    onClear() {
        this.model.value = null;
        this.model.filename = null;

        templates.get(this.getAliasedId('&__placeholder')).then(html => {
            this.image_element.parentNode.innerHTML = Mustache.render(html);
        });

        this.menu.getItem('add').hidden = false;
        this.menu.getItem('replace').hidden = true;
        this.menu.getItem('clear').hidden = true;
    }

    get name() {
        return this.model.name;
    }

    get value() {
        return this.model.value;
    }
};
