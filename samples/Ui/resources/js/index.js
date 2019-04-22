import DialogsPage from "./DialogsPage";
import MenusPage from "./MenusPage";
import DataTablesPage from "./DataTablesPage";

import macaw from 'Manadev_Framework_Js/vars/macaw';
import SampleViewUsingSnackBar from './SampleViewUsingSnackBar';
import templates from "Manadev_Framework_Js/vars/templates";
import merge from 'Manadev_Framework_Js/merge';
import TestSnackBarViewModel from "./TestSnackBarViewModel";
import TestSnackBar from "./TestSnackBar";
import SampleViewUsingPopupMenu from "./SampleViewUsingPopupMenu";
import './tests';

merge(window, {
    Manadev_Samples_Ui: {TestSnackBar: TestSnackBarViewModel }
});

macaw.controller(Manadev_Samples_Ui.SampleViewUsingSnackBar, SampleViewUsingSnackBar);
macaw.controller(Manadev_Samples_Ui.TestSnackBar, TestSnackBar);
macaw.controller('.test-popup-menu', SampleViewUsingPopupMenu);

templates.add('snack-bar__test', {route: 'GET /snack-bars/test'});

macaw.controller('body.-tests-ui-menus', MenusPage);
macaw.controller('body.-tests-ui-dialogs', DialogsPage);
macaw.controller('body.-tests-ui-data-tables', DataTablesPage);


