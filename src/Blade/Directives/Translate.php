<?php

declare(strict_types=1);

namespace Osm\Framework\Blade\Directives;


class Translate extends Directive
{
    public string $name = '__';

    public function render(string $expression): string {
        return "<?php echo \Osm\__($expression) ?>";
    }
}