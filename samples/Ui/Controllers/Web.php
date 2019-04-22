<?php

namespace Manadev\Samples\Ui\Controllers;

use Manadev\App\App\Views\Backend\DataFormContainer;
use Manadev\App\App\Views\Backend\DataTableContainer;
use Manadev\Framework\Http\Controller;
use Manadev\Framework\Views\View;
use Manadev\Ui\SnackBars\Views\SnackBar;

class Web extends Controller
{
    public function typographyPage() {
        return m_layout('base', [
            '#page' => [
                'title' => m_("Typography"),
                'content' => View::new(['template' => 'Manadev_Samples_Ui.typography', 'id_' => null]),
            ],
        ]);
    }

    public function buttonPage() {
        return m_layout('base', [
            '#page' => [
                'title' => m_("Buttons"),
                'content' => View::new(['template' => 'Manadev_Samples_Ui.buttons', 'id_' => null]),
            ],
        ]);
    }

    public function snackBarPage() {
        return m_layout('base', [
            '#page' => [
                'title' => m_("Snack Bars"),
                'content' => View::new(['template' => 'Manadev_Samples_Ui.snack-bars', 'id_' => null]),
            ],
        ]);
    }

    public function snackBarTemplate() {
        return SnackBar::new([
            'template' => 'Manadev_Samples_Ui.snack-bars.test-snack-bar',
            'view_model' => 'Manadev_Samples_Ui.TestSnackBar',
        ]);
    }

    public function unitTestPage() {
        return m_layout('test');
    }

    public function menusPage() {
        return m_layout('tests_ui_menus', [
            '#page' => [
                'title' => m_("Menus"),
            ],
        ]);
    }

    public function dialogsPage() {
        return m_layout('tests_ui_dialogs', [
            '#page' => [
                'title' => m_("Dialogs"),
            ],
        ]);
    }
}