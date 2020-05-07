import Item from "./Item";

export default class CommandItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'click &__button': 'onButtonClick',
        });
    }

    get mobile_menu_events() {
        return Object.assign({}, super.mobile_menu_events, {
            'menuitem:command': 'onMobileItemCommand',
        });
    }

    onButtonClick() {
        this.trigger('command');
    }

    onMobileItemCommand(e) {
        if (e.detail.name !== this.name) {
            return;
        }

        this.trigger('command');
    }
};
