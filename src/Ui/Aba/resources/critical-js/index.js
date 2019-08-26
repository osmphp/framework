import merge from 'Osm_Framework_Js/merge';
import getViewPortRect from './getViewPortRect';

merge(window, {
    Osm_Ui_Aba: { getViewPortRect }
});
