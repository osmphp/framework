import Item from "../Item";

export default class UploadCommandItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'button:upload &__button': 'onButtonUpload',
        });
    }

    onButtonUpload(e) {
        this.trigger('upload', {files: e.detail.files});
    }
};
