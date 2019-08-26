<?php

namespace Osm\Framework\Views;

use Illuminate\View\Compilers\BladeCompiler as LaravelCompiler;

class BladeCompiler extends LaravelCompiler
{
    protected function compileInclude($expression)
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->render({$expression}); ?>";
    }

}