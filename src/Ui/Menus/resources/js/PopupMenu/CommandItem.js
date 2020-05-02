import Item from "./Item";

export default class CommandItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click': 'onClick',
        });
    }

    onClick() {
        this.trigger('command');
        this.menu.close();
    }
};
