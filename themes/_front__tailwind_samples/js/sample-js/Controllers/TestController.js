import Controller from "../../js/Controller";
import {register} from '../../js/scripts';

export default register('test', class TestController extends Controller {
    clicked = false;

    get events() {
        return Object.assign({}, super.events, {
            'click': 'onClicked',
        });
    }

    onClicked() {
        this.clicked = true;
    }
});