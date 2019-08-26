<?php

namespace Osm\Framework\Migrations;

use Illuminate\Console\OutputStyle;
use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Modules\ModuleHelper;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;

/**
 * @property OutputStyle $output
 * @property string[] $modules
 * @property string[] $steps
 * @property bool $fresh
 *
 * @property int $batch_number
 * @property int $files_processed
 *
 * Dependencies:
 *
 * @property ModuleHelper $module_helper
 *
 * Calculated properties:
 *
 * @property BaseModule[] $modules_
 * @property Db $db
 *
 * Temp:
 *
 * @property string $step @temp
 * @property BaseModule $module @temp
 * @property string $path @temp
 * @property string $name @temp
 * @property Migration $instance @temp
 */
class Migrator extends Object_
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'steps': return ['schema', 'data'];
            case 'modules_':
                return empty($this->modules)
                    ? $osm_app->modules
                    : $this->module_helper->getModulesAndDependencies($this->modules);
            case 'module_helper': return $osm_app[ModuleHelper::class];
            case 'db': return $osm_app->db;
        }
        return parent::default($property);
    }

    public function install() {
        $schema = $this->db->schema;
        if ($schema->hasTable('migrations')) {
            return;
        }

        $schema->create('migrations',  function (Blueprint $table) {
            $table->string('module');
            $table->string('step');
            $table->string('migration');
            $table->integer('batch');

            $table->unique(['module', 'step', 'migration']);
            $table->index('batch');
        });
    }

    public function migrate() {
        if ($this->fresh) {
            $this->db->schema->dropAllTables();
        }

        $this->install();

        $this->batch_number = $this->getLastBatchNumber() + 1;
        $this->files_processed = 0;

        foreach ($this->steps as $step) {
            $this->step = $step;
            $this->migrateStep();
        }

        if ($this->output && !$this->files_processed) {
            $this->output->writeln((string)m_("<info>Nothing to migrate.</info>"));
        }
    }

    protected function migrateStep() {
        global $osm_app; /* @var App $osm_app */

        foreach ($this->modules_ as $module) {
            $this->module = $module;
            $this->path = $osm_app->path("{$module->path}/migrations/{$this->step}");
            if (!is_dir($this->path)) {
                continue;
            }

            $this->migrateModule();
        }
    }

    protected function migrateModule() {
        global $osm_app; /* @var App $osm_app */

        foreach ($this->getMigrationFiles() as $name => $filename) {
            if ($this->isProcessed($name)) {
                continue;
            }

            $this->files_processed++;

            /** @noinspection PhpIncludeInspection */
            require_once $filename;

            $this->name = $name;
            $this->instance = $osm_app->create($this->getClassName($name), ['db' => $this->db]);
            $this->migrateFile();
        }
    }

    protected function migrateFile() {
        $this->instance->up();
        $this->markMigrationAsProcessed();

        if ($this->output) {
            $this->output->writeln((string)m_("<info>Migrated: </info> :step :module :migration", [
                'step' => $this->step,
                'module' => $this->module->name,
                'migration' => $this->name,
            ]));
        }
    }

    public function migrateBack() {
        $this->install();

        $this->files_processed = 0;
        foreach (array_reverse($this->steps) as $step) {
            $this->step = $step;
            $this->migrateStepBack();
        }

        if ($this->output && !$this->files_processed) {
            $this->output->writeln((string)m_("<info>Nothing to migrate back.</info>"));
        }
    }

    protected function migrateStepBack() {
        global $osm_app; /* @var App $osm_app */

        foreach (array_reverse($this->modules_) as $module) {
            $this->module = $module;
            $this->path = $osm_app->path("{$module->path}/migrations/{$this->step}");
            if (!is_dir($this->path)) {
                continue;
            }

            $this->migrateModuleBack();
        }
    }

    protected function migrateModuleBack() {
        global $osm_app; /* @var App $osm_app */

        foreach ($this->getAllModuleMigrations() as $name) {
            $this->files_processed++;

            /** @noinspection PhpIncludeInspection */
            require_once $osm_app->path("{$this->module->path}/migrations/{$this->step}/{$name}.php");

            $this->name = $name;
            $this->instance = $osm_app->create($this->getClassName($name), ['db' => $this->db]);
            $this->migrateFileBack();
        }
    }

    protected function migrateFileBack() {
        $this->instance->down();
        $this->markMigrationAsRolledBack();
        if ($this->output) {
            $this->output->writeln((string)m_("<info>Migration rolled back: </info>  :step :module :migration", [
                'step' => $this->step,
                'module' => $this->module->name,
                'migration' => $this->name,
            ]));
        }
    }

    protected function getLastBatchNumber() {
        return $this->db->connection->table('migrations')->max('batch');
    }

    /**
     * @return string[]
     */
    protected function getMigrationFiles() {
        $result = [];

        foreach (new \DirectoryIterator($this->path) as $fileInfo) {
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

            $result[$name] = $fileInfo->getPathname();
        }

        ksort($result);

        return $result;
    }

    protected function isProcessed($name) {
        return $this->db->connection->table('migrations')
            ->where('module', $this->module->name)
            ->where('step', $this->step)
            ->where('migration', $name)
            ->exists();
    }

    protected function markMigrationAsProcessed() {
        $this->db->connection->table('migrations')->insert([
            'module' => $this->module->name,
            'step' => $this->step,
            'migration' => $this->name,
            'batch' => $this->batch_number,
        ]);
    }

    protected function getAllModuleMigrations() {
        return $this->db->connection->table('migrations')
            ->where('module', $this->module->name)
            ->where('step', $this->step)
            ->orderBy('migration', 'desc')
            ->pluck('migration');
    }

    protected function markMigrationAsRolledBack() {
        $this->db->connection->table('migrations')
            ->where('module', $this->module->name)
            ->where('step', $this->step)
            ->where('migration', $this->name)
            ->delete();
    }

    /**
     * @param $name
     * @return string
     */
    protected function getClassName($name) {
        $pos = strpos($name, '_');

        return str_replace('_', '\\', $this->module->name) .
            '\\Migrations\\' . studly_case($this->step) . '\\' . studly_case(substr($name, $pos + 1));
    }
}