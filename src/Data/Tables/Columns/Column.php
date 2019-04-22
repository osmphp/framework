<?php

namespace Manadev\Data\Tables\Columns;

use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $type @required @part
 * @property string $title @required @part
 * @property mixed $value @part
 * @property string $references @part
 * @property string $on_update @part
 * @property string $on_delete @part
 * @property int $partition @part
 * @property string $data_type @required @part
 * @property int $id @required @part
 *
 * Type-specific properties:
 *
 * @property bool $unsigned @part
 * @property int $length @part
 * @property int $precision @part
 * @property int $scale @part
 *
 * @property bool $exists @required
 *
 */
class Column extends Object_
{
    const INT_ = 'int';
    const BOOL_ = 'bool';
    const STRING_ = 'string';
    const TEXT = 'text';
    const DATE = 'date';
    const DECIMAL = 'decimal';

    /**
     * @required @part
     * @var bool
     */
    public $pinned = false;
    /**
     * @required @part
     * @var bool
     */
    public $required = false;
    /**
     * @required @part
     * @var bool
     */
    public $index = false;
    /**
     * @required @part
     * @var bool
     */
    public $unique = false;

    protected function default($property) {
        switch ($property) {
            case 'index': return $this->references !== null;
            case 'exists': return $this->parent instanceof Columns;
        }
        return parent::default($property);
    }

    public function value($value) {
        $this->value = $value;
        return $this;
    }

    public function pinned($value = true) {
        $this->pinned = $value;
        return $this;
    }

    public function references($value) {
        $this->references = $value;
        return $this;
    }

    public function on_update($value) {
        $this->on_update = $value;
        return $this;
    }

    public function on_delete($value) {
        $this->on_delete = $value;
        return $this;
    }

    public function index($value = true) {
        $this->index = $value;
        return $this;
    }

    public function unique($value = true) {
        $this->unique = $value;
        return $this;
    }

    public function required($value = true) {
        $this->required = $value;
        return $this;
    }

    public function partition($value) {
        $this->partition = $value;

        return $this;
    }

    public function title($value) {
        $this->title = $value;

        return $this;
    }

    public function unsigned($value = true) {
        $this->unsigned = $value;

        return $this;
    }

}