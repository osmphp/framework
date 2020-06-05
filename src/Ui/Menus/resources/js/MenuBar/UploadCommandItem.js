import Item from "../Item";

export default class UploadCommandItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'button:upload &__button': 'onButtonUpload',
            'button:uploading &__button': 'onButtonUploading',
        });
    }

    onButtonUpload(e) {
        this.trigger('upload', {files: e.detail.files});
    }

    onButtonUploading(e) {
        this.trigger('uploading', e.detail);
    }
};
