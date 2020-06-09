<?php

namespace Osm\Samples\Ui\Controllers;

use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Http\Controller;
use Osm\Framework\Views\View;
use Osm\Ui\Images\Views\Image;
use Osm\Ui\SnackBars\Views\SnackBar;

/**
 * @property Files $files @required
 */
class Web extends Controller
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
        }

        return parent::default($property);
    }

    public function typographyPage() {
        return osm_layout('base', [
            '#page' => [
                'title' => osm_t("Typography"),
            ],
            '#content' => [
                'items' => [
                    'typography' => View::new(['template' => 'Osm_Samples_Ui.typography', 'id_' => null]),
                ],
            ],
        ]);
    }

    public function buttonPage() {
        return osm_layout('base', [
            '#page' => [
                'title' => osm_t("Buttons"),
            ],
            '#content' => [
                'items' => [
                    'buttons' => View::new(['template' => 'Osm_Samples_Ui.buttons', 'id_' => null]),
                ],
            ],
        ]);
    }

    public function snackBarPage() {
        return osm_layout('base', [
            '#page' => [
                'title' => osm_t("Snack Bars"),
            ],
            '#content' => [
                'items' => [
                    'snack-bars' => View::new(['template' => 'Osm_Samples_Ui.snack-bars', 'id_' => null]),
                ],
            ],
        ]);
    }

    public function snackBarTemplate() {
        return SnackBar::new([
            'template' => 'Osm_Samples_Ui.snack-bars.test-snack-bar',
            'view_model' => 'Osm_Samples_Ui.TestSnackBar',
        ]);
    }

    public function unitTestPage() {
        return osm_layout('test');
    }

    public function menusPage() {
        return osm_layout('tests_ui_menus', [
            '#page.title' => osm_t("Menus"),
        ]);
    }

    public function dialogsPage() {
        return osm_layout('tests_ui_dialogs', [
            '#page' => [
                'title' => osm_t("Dialogs"),
            ],
        ]);
    }

    public function tablesPage() {
        return osm_layout('tests_ui_tables', [
            '#page' => [
                'title' => osm_t("Data Tables"),
            ],
        ]);
    }

    public function tableRows() {
        $layout = osm_layout('tests_ui_tables', [
            '#data_table' => [
                'render_rows' => true,
                'offset' => $this->query['_offset'],
                'limit' => $this->query['_limit'],
            ],
        ]);

        return $layout->select('#data_table');
    }

    public function colorsPage() {
        return osm_layout('tests_ui_colors', [
            '#page.title' => osm_t("Colors"),

            // bind data to views
        ]);
    }

    public function uploadsPage() {
        return osm_layout('tests_ui_uploads', [
            '#page.title' => osm_t("Uploads"),

            // bind data to views
        ]);
    }

    public function upload() {
        $this->files->validateImage();

        return (string)Image::new([
            'id_' => null,
            'width' => 160,
            'height' => 160,
            'file_' => $this->files->upload(Files::PUBLIC),
        ]);
    }
}