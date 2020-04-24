import merge from 'Osm_Framework_Js/merge';
import Form from './Form';
import Fields from './Fields';
import StringField from './StringField';
import PriceField from './PriceField';
import DateField from './DateField';
import PasswordField from './PasswordField';
import DropdownField from './DropdownField';
import TextField from './TextField';

merge(window, {
    Osm_Ui_Forms: { Form, Fields, StringField, PriceField, DateField,
        PasswordField, DropdownField, TextField }
});
