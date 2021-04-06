<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Illuminate\Database\Query\Builder as DbQuery;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Illuminate\Database\Schema\Blueprint as TableBlueprint;
use Osm\Framework\Search\Blueprint as IndexBlueprint;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $sheet_name #[Serialized]
 * @property string $name #[Serialized]
 * @property string $type_name #[Serialized]
 * @property ?string $type_data #[Serialized]
 * @property Type $type
 * @property int $partition_no #[Serialized]
 * @property int $partition_weight
 * @property bool $filterable #[Serialized]
 * @property Module $module
 */
class Column extends Object_
{
    const INT_ = 'int';
    const STRING_ = 'string';
    const FLOAT_ = 'float';
    const BOOL_ = 'bool';
    const TEXT_ = 'text';
    const DATETIME_ = 'datetime';

    public function createInTable(TableBlueprint $table): void {
        throw new NotImplemented();
    }

    public function createInIndex(IndexBlueprint $index): void {
        throw new NotImplemented();
    }

    public function toDbRecord(): array {
        $data = [];

        foreach ($this->__class->properties as $property) {
            if (isset($property->attributes[Serialized::class])) {
                $data[$property->name] = $this->{$property->name};
            }
        }

        return $data;
    }

    public function save(array &$values, \stdClass $data): void {
        $this->type->save($values, $data);
    }

    public function index(array &$values, \stdClass $data): void {
        throw new NotImplemented();
    }

    public function select(DbQuery $query): void {
        $query->addSelect("this.{$this->name}");
    }

    public function insertIntoChildSheet(\stdClass $data): void {
        $this->type->insertIntoChildSheet($data);
    }

    /** @noinspection PhpUnused */
    protected function get_partition_weight(): int {
        if ($this->type == static::STRING_) {
            return 1;
        }

        return 0;
    }

    /** @noinspection PhpUnused */
    protected function get_class_name(): string {
        return $this->__class->name;
    }

    public function partition_no(int $value): static {
        $this->partition_no = $value;

        return $this;
    }

    public function filterable(bool $value = true): static {
        $this->filterable = $value;

        return $this;
    }

    /** @noinspection PhpUnused */
    protected function get_filterable(): bool {
        return false;
    }

    protected function get_type(): Type {
        $new = "{$this->module->type_classes[$this->type_name]}::new";
        $data = $this->type_data ? (array)json_decode($this->type_data) : [];
        $data['column'] = $this;
        return $new($data);
    }

    /** @noinspection PhpUnused */
    protected function get_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }
}