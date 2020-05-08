import Item from "../Item";

export default class CommandItem extends Item {
    get events() {
        return Object.assign({}, super.events, {
            'button:click &__button': 'onButtonClick',
        });
    }

    onButtonClick() {
        this.trigger('command');
    }
};
