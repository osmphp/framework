import merge from 'Osm_Framework_Js/merge';
import Form from './Form';
import Fields from './Fields';
import StringField from './StringField';

merge(window, {
    Osm_Ui_Forms: { Form, Fields, StringField }
});
