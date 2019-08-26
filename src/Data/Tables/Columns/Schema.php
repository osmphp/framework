<?php

namespace Osm\Data\Tables\Columns;

use Illuminate\Database\Schema\Blueprint as SchemaBlueprint;
use Illuminate\Support\Fluent;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;

/**
 * @property Column $column @temp
 * @property SchemaBlueprint $table @temp
 */
class Schema extends Object_
{
    /**
     * @see \Osm\Data\Tables\Columns\Column::$type @handler
     *
     * @param Column $column
     * @param SchemaBlueprint $table
     */
    public function create(Column $column, SchemaBlueprint $table) {
        $this->column = $column;
        $this->table = $table;

        switch ($column->type) {
            case Column::BOOL_: $this->createBoolColumn(); break;
            case Column::INT_: $this->createIntColumn(); break;
            case Column::STRING_: $this->createStringColumn(); break;
            case Column::TEXT: $this->createTextColumn(); break;
            case Column::DATE: $this->createDateColumn(); break;
            case Column::DECIMAL: $this->createDecimalColumn(); break;
            default:
                throw new NotSupported(m_("Column type ':type' not supported", ['type' => $this->column->type]));
        }
    }

    protected function createBoolColumn() {
        if ($this->column->required) {
            $column = $this->table->boolean($this->column->name);
            $this->setDefault($column);
        }
        else {
            $this->table->boolean($this->column->name)->nullable();
        }

        $this->createIndex();
        $this->createReference();
    }

    protected function createIntColumn() {
        if ($this->column->name == 'id') {
            $this->table->increments('id');
            return;
        }

        if ($this->column->required) {
            $column = $this->table->integer($this->column->name)->unsigned($this->column->unsigned ?? false);
            $this->setDefault($column);
        }
        else {
            $this->table->integer($this->column->name)->unsigned($this->column->unsigned ?? false)->nullable();
        }

        $this->createIndex();
        $this->createReference();
    }

    protected function createStringColumn() {
        if ($this->column->required) {
            $column = $this->table->string($this->column->name, $this->column->length);
            $this->setDefault($column);
        }
        else {
            $this->table->string($this->column->name, $this->column->length)->nullable();
        }

        $this->createIndex();
        $this->createReference();
    }

    protected function createDateColumn() {
        if ($this->column->required) {
            $column = $this->table->date($this->column->name);
            $this->setDefault($column);
        }
        else {
            $this->table->date($this->column->name)->nullable();
        }

        $this->createIndex();
    }

    protected function createDecimalColumn() {
        if ($this->column->required) {
            $column = $this->table->decimal($this->column->name, $this->column->precision, $this->column->scale);
            $this->setDefault($column);
        }
        else {
            $this->table->decimal($this->column->name, $this->column->precision, $this->column->scale)->nullable();
        }

        $this->createIndex();
    }

    protected function createTextColumn() {
        $column = $this->table->longText($this->column->name);
        if (!$this->column->required) {
            $column->nullable();
        }
    }

    protected function createIndex(){
        if ($this->column->unique) {
            $this->table->unique($this->column->name);
        }
        elseif ($this->column->index) {
            $this->table->index($this->column->name);
        }
    }

    protected function createReference() {
        if ($this->column->references) {
            list($foreignTable, $foreignColumn) = explode('.', $this->column->references);
            $foreign = $this->table->foreign($this->column->name)->references($foreignColumn)->on($foreignTable);
            if ($this->column->on_update) {
                $foreign->onUpdate($this->column->on_update);
            }
            if ($this->column->on_delete) {
                $foreign->onDelete($this->column->on_delete);
            }
        }
    }

    protected function setDefault(Fluent $column) {
        if ($this->column->value !== null) {
            $column->default($this->column->value);
        }
    }

}