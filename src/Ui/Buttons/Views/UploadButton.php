<?php

namespace Osm\Ui\Buttons\Views;

class UploadButton extends Button
{
    protected function default($property) {
        switch ($property) {
            case 'title': return osm_t("Upload");
        }

        return parent::default($property);
    }
}