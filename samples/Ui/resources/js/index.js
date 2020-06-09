import UploadsPage from "./UploadsPage";
import ColorsPage from "./ColorsPage";
import macaw from "Osm_Framework_Js/vars/macaw";
import DialogsPage from "./DialogsPage";
import MenusPage from "./MenusPage";
import TablesPage from "./TablesPage";

import SampleViewUsingSnackBar from './SampleViewUsingSnackBar';
import templates from "Osm_Framework_Js/vars/templates";
import merge from 'Osm_Framework_Js/merge';
import TestSnackBarViewModel from "./TestSnackBarViewModel";
import TestSnackBar from "./TestSnackBar";
import SampleViewUsingPopupMenu from "./SampleViewUsingPopupMenu";
import './tests';

merge(window, {
    Osm_Samples_Ui: {TestSnackBar: TestSnackBarViewModel }
});

macaw.controller(Osm_Samples_Ui.SampleViewUsingSnackBar, SampleViewUsingSnackBar);
macaw.controller(Osm_Samples_Ui.TestSnackBar, TestSnackBar);
macaw.controller('.test-popup-menu', SampleViewUsingPopupMenu);

templates.add('snack-bar__test', {route: 'GET /snack-bars/test'});

macaw.controller('body.-tests-ui-menus', MenusPage);
macaw.controller('body.-tests-ui-dialogs', DialogsPage);
macaw.controller('body.-tests-ui-tables', TablesPage);

macaw.controller('body.-tests-ui-colors', ColorsPage);
macaw.controller('body.-tests-ui-uploads', UploadsPage);
