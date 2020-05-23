import macaw from "Osm_Framework_Js/vars/macaw";
import Button from "./Button";
import UploadButton from "./UploadButton";

macaw.controller('.button:not(.-upload)', Button);
macaw.controller(Osm_Ui_Buttons.UploadButton, UploadButton);
