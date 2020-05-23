import ajax from "Osm_Framework_Js/ajax";
import formatString from "Osm_Framework_Js/formatString";

export default function upload(route, inputElement, message) {
    let result = Promise.all(Array.prototype.map.call(inputElement.files, file => {
        return ajax(route, {
            file: file,
            snackbar_message: formatString(message, {file: file.name}),
        });
    }));

    inputElement.value = "";

    return result;
}