<?php

namespace Osm\Framework\Views;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine as LaravelCompilerEngine;
use Osm\Core\App;
use Osm\Core\Classes\Statements;
use Osm\Core\Classes\RemoveUseStatements;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node\Stmt;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * @property string[] $compiled_templates
 * @property string $filename
 * @property BladeCompiler $compiler
 * @property int $timestamp
 * @property ViewFactory $env
 * @property Module $module
 */
class CompilerEngine extends LaravelCompilerEngine
{
    public function __get($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'compiled_templates': return $this->compiled_templates = $this->getCompiledTemplates();
            case 'filename': return $m_app->path("{$m_app->temp_path}/cache/blade_templates.php");
            case 'timestamp': return $this->timestamp = filemtime($this->filename);
            case 'module': return $this->module = $m_app->modules['Osm_Framework_Views'];
            case 'env': return $this->env = $this->module->laravel_view;
        }
        return null;
    }

    public function get($path, array $data = []) {
        $this->lastCompiled[] = $path;

        $templates = $this->compiled_templates;
        $exists = isset($templates[$path]);

        if ($exists && filemtime($path) > $this->timestamp) {
            $this->clear();
            $exists = false;
        }

        if (!$exists) {
            $this->compile($path);
        }

        $results = $this->evaluatePath($path, $data);

        array_pop($this->lastCompiled);

        return $results;
    }

    protected function getCompiledTemplates() {
        if (!is_file($this->filename)) {
            return [];
        }

        $templates = [];

        $fh = fopen($this->filename, 'r');
        try {
            flock($fh, LOCK_SH);

            /** @noinspection PhpIncludeInspection */
            include $this->filename;
        }
        finally {
            fclose($fh);
        }

        return $templates;
    }

    protected function clear() {
        unlink($this->filename);
        unset($this->timestamp);
        $this->compiled_templates = [];
    }

    protected function compile($path) {
        $code = $this->compiler->compileString(file_get_contents($path));

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $statements = (new Statements($parser->parse($code)))
            ->each(new NameResolver())
            ->each(new RemoveUseStatements())
            ->stmts;

        $name = sha1($path);
        $printer = new PrettyPrinter\Standard();
        $code = "\$templates['{$path}'] = 'blade_{$name}';\n" .
            "if (!function_exists('blade_{$name}')) {function blade_{$name}(\$__env, \$__data) {\n" .
            "extract(\$__data, EXTR_SKIP); ?>\n" .
            $printer->prettyPrintFile($statements) .
            ($statements[count($statements) - 1] instanceof Stmt\InlineHTML ? '<?php' : '') .
            "\n}\n}\n\n";

        if (!is_file($this->filename)) {
            file_put_contents($this->filename, "<?php\n\n");
        }
        file_put_contents($this->filename, $code, FILE_APPEND | LOCK_EX);
        opcache_invalidate($this->filename, true);
        $this->compiled_templates = $this->getCompiledTemplates();
        $this->timestamp = filemtime($this->filename);
    }

    protected function evaluatePath($path, $data)
    {
        $obLevel = ob_get_level();

        ob_start();

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        try {
            call_user_func($this->compiled_templates[$path], $this->env, $data);
        } catch (\Exception $e) {
            $this->handleViewException($e, $obLevel);
        } catch (\Throwable $e) {
            $this->handleViewException(new FatalThrowableError($e), $obLevel);
        }

        return ltrim(ob_get_clean());
    }
}