import ajax from "Osm_Framework_Js/ajax";
import formatString from "Osm_Framework_Js/formatString";
import snackBars from "Osm_Ui_SnackBars/vars/snackBars";

export default function upload(route, inputElement, message, query) {
    let result = Promise.all(Array.prototype.map.call(inputElement.files, file => {
        return ajax(route, {
            file: file,
            headers: {
                'Content-Name': file.name,
            },
            query: query,
            snackbar_message: formatString(message, {file: file.name}),
        }).catch(json => {
            snackBars.showMessage(json.message);
            return Promise.reject();
        });
    }));

    inputElement.value = "";

    return result;
}