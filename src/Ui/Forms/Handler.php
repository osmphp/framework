<?php

namespace Osm\Ui\Forms;

use Osm\Core\Object_;
use Osm\Framework\Views\View;
use Osm\Ui\Forms\Views\Form;

/**
 * @handler_class
 * @property Form $form @required
 */
abstract class Handler extends Object_
{
    protected function handleForm() {
        $this->handleView($this->form);
    }

    /**
     * @param View $view
     */
    protected function handleView($view) {
        if ($view instanceof FormPart) {
            $this->handleFormPart($view);
        }

        foreach ($view->items ?: [] as $childView) {
            $this->handleView($childView);
        }
    }

    abstract protected function handleFormPart(FormPart $view);

}