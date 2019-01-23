import Controller from "./Controller";
import macaw from "./vars/macaw";

export default class Detacher extends Controller {
    onDetach() {
        macaw.beforeRemoving(this.model.element_to_be_detached);
        this.element.removeChild(this.model.element_to_be_detached);
    }
}