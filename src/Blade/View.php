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
 *
 * @uses RenderTime
 */
class View extends Object_
{
    /**
     * `true` if the view is created using `view()` helper function
     * @var bool
     */
    public bool $rendering = false;

    protected function get_template(): string {
        throw new Required(__METHOD__);
    }

    protected function get_data(): array {
        throw new Required(__METHOD__);
    }

    protected function default(string $property): mixed
    {
        if (!$this->rendering &&
            isset($this->__class->properties[$property]
                ->attributes[RenderTime::class]))
        {
            throw new RenderingError(__(
                "Only use `:property` render-time property on objects created using `view()` helper function.",
                ['property' => "{$this->__class->name}::\${$property}"]));
        }

        return parent::default($property);
    }

    public function __wakeup(): void
    {
    }
}