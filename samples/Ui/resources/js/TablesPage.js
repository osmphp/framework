import Controller from "Osm_Framework_Js/Controller";

export default class TablesPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            // handle JS events here
        });
    }
};