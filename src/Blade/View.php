<?php

namespace Osm\Framework\Blade;

use Osm\Core\Exceptions\Required;
use Osm\Core\Object_;
use Osm\Framework\Blade\Exceptions\RenderingError;
use function Osm\__;
use Osm\Framework\Blade\Attributes\RenderTime;

/**
 * @property string $template #[RenderTime]
 * @property array $data #[RenderTime]
 * @property bool $rendering `true` if the view is created using `view()`
 *      helper function, `null` otherwise
 *
 * @uses RenderTime
 */
class View extends Object_
{
    protected function get_template(): string {
        throw new Required(__METHOD__);
    }

    protected function get_data(): array {
        throw new Required(__METHOD__);
    }

    protected function default(string $property): mixed
    {
        if (!$this->rendering) {
            throw new RenderingError(__(
                "Only use `:property` render-time property on objects created using `view()` helper function.",
                ['property' => $property]));
        }

        return parent::default($property);
    }
}