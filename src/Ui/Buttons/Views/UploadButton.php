<?php

namespace Osm\Ui\Buttons\Views;

/**
 * @property string $accept @part
 * @property bool $multi_select @part
 * @property string $route @required @part
 * @property string $message @part
 */
class UploadButton extends Button
{
    public $template = 'Osm_Ui_Buttons.upload_button';
    public $view_model = 'Osm_Ui_Buttons.UploadButton';

    protected function default($property) {
        switch ($property) {
            case 'model': return [
                'route' => $this->route,
                'message' => (string)($this->message ?:
                    osm_t("Uploading ':file' ...")),
            ];
        }
        return parent::default($property);
    }
}