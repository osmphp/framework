import Item from "./Item";

export default class LinkItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click': 'onClick',
        });
    }

    onClick() {
        this.menu.close();
    }
};
