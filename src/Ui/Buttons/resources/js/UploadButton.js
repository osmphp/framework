import Controller from "Osm_Framework_Js/Controller";
import upload from "./upload";

export default class UploadButton extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            'change .button__file-input': 'onUpload',
        });
    }

    onUpload(e) {
        let uploading = {};
        this.element.dispatchEvent(new CustomEvent('button:uploading',
            {detail: uploading}));

        upload(this.model.route, e.currentTarget, this.model.message,
            uploading.query)
            .then(files => {
                this.element.dispatchEvent(new CustomEvent('button:upload', {
                    detail: {files}
                }));
            });
    }
};