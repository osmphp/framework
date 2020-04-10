import Controller from "Osm_Framework_Js/Controller";

export default class ContainersPage extends Controller {
    get events() {
        return Object.assign({}, super.events, {
            // handle JS events here
        });
    }
};