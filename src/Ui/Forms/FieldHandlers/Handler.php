<?php

namespace Osm\Ui\Forms\FieldHandlers;

use Osm\Core\Object_;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Forms\Views\Field;
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
     * @param Field|Container $view
     */
    protected function handleView($view) {
        if ($view instanceof Field) {
            $this->handleField($view);
        }

        foreach ($view->items ?: [] as $childView) {
            $this->handleView($childView);
        }
    }

    abstract protected function handleField(Field $field);

}