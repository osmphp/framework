import ListsPage from "./ListsPage";
import UploadsPage from "./UploadsPage";
import ColorsPage from "./ColorsPage";
import macaw from "Osm_Framework_Js/vars/macaw";
import DialogsPage from "./DialogsPage";
import MenusPage from "./MenusPage";
import TablesPage from "./TablesPage";

import SampleViewUsingSnackBar from './SampleViewUsingSnackBar';
import templates from "Osm_Framework_Js/vars/templates";
import TestSnackBar from "./TestSnackBar";
import SampleViewUsingPopupMenu from "./SampleViewUsingPopupMenu";
import './tests';

macaw.controller('.sample-view-using-snackbar', SampleViewUsingSnackBar);
macaw.controller('.snack-bar.-sample', TestSnackBar);
macaw.controller('.test-popup-menu', SampleViewUsingPopupMenu);

templates.add('snack-bar__test', {route: 'GET /snack-bars/test'});

macaw.controller('body.-tests-ui-menus', MenusPage);
macaw.controller('body.-tests-ui-dialogs', DialogsPage);
macaw.controller('body.-tests-ui-tables', TablesPage);

macaw.controller('body.-tests-ui-colors', ColorsPage);
macaw.controller('body.-tests-ui-uploads', UploadsPage);
macaw.controller('body.-tests-ui-lists', ListsPage);
