import Field from './Field';

export default class ImageField extends Field {
    get events() {
        return Object.assign({}, super.events, {
            'menuitem:upload:add &___menu': 'onUpload',
            'menuitem:upload:replace &___menu': 'onUpload',
            'menuitem:command:clear &___menu': 'onClear',
        });
    }

    get image_element() {
        return this.element.querySelector('.form-section__image');
    }

    onUpload(e) {
        let file = e.detail.files[0];

        this.model.value = file.uid;
        this.image_element.setAttribute('src', file.url);
        this.element.classList.remove('-empty');
    }

    onClear() {
        this.model.value = null;
        this.element.classList.add('-empty');
        this.image_element.removeAttribute('src');
    }

    get name() {
        return this.model.name;
    }

    get value() {
        return this.model.value;
    }
};
