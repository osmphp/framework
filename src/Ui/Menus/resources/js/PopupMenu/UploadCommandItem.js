import Item from "../Item";
import upload from "Osm_Ui_Buttons/upload";

export default class UploadCommandItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'change .popup-menu__file-input': 'onUpload',
        });
    }

    onUpload(e) {
        let uploading = {};
        this.trigger('uploading', uploading);

        upload(this.model.route, e.currentTarget, this.model.message,
            uploading.query)
            .then(files => {
                this.trigger('upload', {files});
            });
        this.menu.close();
    }
};
