<?php

declare(strict_types=1);

namespace Osm\Framework\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;
use Osm\Core\Attributes\Temp;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use function Osm\__;

/**
 * @property Db $db
 * @property string $table_name
 * @property string[] $method_names
 * @property string $up_method_name #[Temp]
 * @property string $method_name #[Temp]
 * @property BaseModule $module #[Temp]
 * @property string $migration_name #[Temp]
 * @property int $batch_number
 * @property OutputInterface $output
 */
class Migrations extends Object_
{
    #[Temp]
    public int $files_processed = 0;

    public function fresh(): void {
        $schema = $this->db->connection->getSchemaBuilder();

        $schema->dropAllTables();
        $schema->dropAllViews();
    }

    public function up(): void {
        global $osm_app; /* @var App $osm_app */

        $this->init();

        foreach (array_keys($this->method_names) as $upMethodName) {
            $this->up_method_name = $upMethodName;
            $this->method_name = $upMethodName;
            foreach ($osm_app->modules as $module) {
                $this->module = $module;
                $this->moduleUp();
            }
        }
    }

    public function down(array $moduleClassNames): void {
        global $osm_app; /* @var App $osm_app */

        $this->init();

        $modules = empty($moduleClassNames)
            ? $osm_app->modules
            : $this->getModulesAndTheirDependencies($moduleClassNames);

        foreach (array_reverse($this->method_names) as
            $upMethodName => $downMethodName)
        {
            $this->up_method_name = $upMethodName;
            $this->method_name = $downMethodName;
            foreach (array_reverse($modules) as $module) {
                $this->module = $module;
                $this->moduleDown();
            }
        }
    }

    protected function init() {
        if ($this->db->hasTable($this->table_name)) {
            return;
        }

        $this->db->create($this->table_name,  function (Blueprint $table) {
            $table->string('module');
            $table->string('method');
            $table->string('migration');
            $table->integer('batch');

            $table->unique(['module', 'method', 'migration']);
            $table->index('batch');
        });
    }

    /** @noinspection PhpUnused */
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    /** @noinspection PhpUnused */
    protected function get_table_name(): string {
        return 'migrations';
    }

    /** @noinspection PhpUnused */
    protected function get_method_names(): array {
        return ['create' => 'drop', 'insert' => 'delete'];
    }

    /**
     * @param array $moduleClassNames
     * @return BaseModule[]
     */
    protected function getModulesAndTheirDependencies(array $moduleClassNames): array {
        global $osm_app; /* @var App $osm_app */

        $populatedModuleClassNames = array_flip($moduleClassNames);

        while (!empty($moduleClassNames)) {
            $dependentModuleClassNames = [];

            foreach ($osm_app->modules as $module) {
                if (isset($populatedModuleClassNames[$module->class_name])) {
                    // $module is already handled
                    continue;
                }

                $requires = array_intersect($moduleClassNames, $module::$requires);
                if (empty($requires)) {
                    // $module is not directly dependent on
                    continue;
                }

                $populatedModuleClassNames[$module->class_name] = true;
                $dependentModuleClassNames[$module->class_name] = true;
            }

            $moduleClassNames = array_keys($dependentModuleClassNames);
        }

        return array_filter($osm_app->modules, fn(BaseModule $module) =>
            isset($populatedModuleClassNames[$module->class_name]));
    }

    protected function moduleUp(): void {
        foreach ($this->getModuleMigrations() as $name => $filename) {
            $this->migration_name = $name;
            if ($this->isProcessed()) {
                continue;
            }

            $new = "{$this->getClassName()}::new";
            $migration = $new();

            $migration->{$this->method_name}();
            $this->markAsProcessed();

            $this->files_processed++;
            $this->output->writeln(__("Migrated: :method :module :migration", [
                'method' => $this->method_name,
                'module' => $this->module->name,
                'migration' => $this->migration_name,
            ]));
        }
    }

    protected function moduleDown(): void {
        foreach ($this->getProcessedModuleMigrations() as $migrationName) {
            $this->migration_name = $migrationName;

            $new = "{$this->getClassName()}::new";
            $migration = $new();

            $migration->{$this->method_name}();
            $this->unmarkAsProcessed();

            $this->files_processed++;
            $this->output->writeln(__("Migrated back: :method :module :migration", [
                'method' => $this->method_name,
                'module' => $this->module->name,
                'migration' => $this->migration_name,
            ]));
        }
    }

    /** @noinspection PhpUnused */
    protected function get_batch_number(): int {
        $lastBatchNumber = $this->db->connection->table($this->table_name)
            ->max('batch') ?? 0;

        return $lastBatchNumber + 1;
    }

    /** @noinspection PhpUnused */
    protected function get_output(): OutputInterface {
        return new BufferedOutput();
    }

    protected function getModuleMigrations(): array {
        global $osm_app; /* @var App $osm_app */

        $filenames = [];

        $path = "{$osm_app->paths->project}/{$this->module->path}/Migrations";
        if (!is_dir($path)) {
            return $filenames;
        }

        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }

            if ($fileInfo->getExtension() != 'php') {
                continue;
            }

            $name = pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);
            if (($pos = strpos($name, '_')) === false) {
                continue;
            }

            $filenames[$name] = $fileInfo->getPathname();
        }

        ksort($filenames);

        return $filenames;
    }

    protected function isProcessed(): bool {
        return $this->db->connection->table($this->table_name)
            ->where('module', $this->module->name)
            ->where('method', $this->up_method_name)
            ->where('migration', $this->migration_name)
            ->exists();
    }

    protected function getClassName(): string {
        return str_replace('_', '\\', $this->module->name) .
            "\\Migrations\\{$this->migration_name}";
    }

    protected function markAsProcessed(): void {
        $this->db->connection->table($this->table_name)->insert([
            'module' => $this->module->name,
            'method' => $this->up_method_name,
            'migration' => $this->migration_name,
            'batch' => $this->batch_number,
        ]);
    }

    protected function getProcessedModuleMigrations(): array {
        return $this->db->connection->table($this->table_name)
            ->where('module', $this->module->name)
            ->where('method', $this->up_method_name)
            ->orderBy('migration', 'desc')
            ->pluck('migration')
            ->toArray();
    }

    protected function unmarkAsProcessed() {
        $this->db->connection->table($this->table_name)
            ->where('module', $this->module->name)
            ->where('method', $this->up_method_name)
            ->where('migration', $this->migration_name)
            ->delete();
    }
}