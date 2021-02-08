<?php

declare(strict_types=1);

namespace Osm\Framework\Console;

use Osm\Core\App;
use Osm\Core\Object_;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Osm\Framework\Console\Attributes\Option as OptionAttribute;
use Osm\Framework\Console\Attributes\Argument as ArgumentAttribute;

/**
 * @property string $name
 * @property Option[] $options
 * @property Argument[] $arguments
 *
 * @property InputInterface $input
 * @property OutputInterface $output
 */
class Command extends Object_
{
    public function run(): void {
    }

    /** @noinspection PhpUnused */
    protected function get_options(): array {
        $options = [];

        foreach ($this->__class->properties as $property) {
            if (!isset($property->attributes[OptionAttribute::class])) {
                continue;
            }

            /* @var OptionAttribute $attribute */
            $attribute = $property->attributes[OptionAttribute::class];

            $options[$property->name] = Option::new([
                'name' => $property->name,
                'shortcut' => $attribute->shortcut,
                'mode' => $property->array
                    ? InputOption::VALUE_IS_ARRAY
                    : ($property->type == 'bool'
                        ? InputOption::VALUE_NONE
                        : ($property->nullable
                            ? InputOption::VALUE_OPTIONAL
                            : InputOption::VALUE_REQUIRED
                        )
                    ),
                'description' => $property->description,
                'default' => $attribute->default,
            ]);
        }

        return $options;
    }

    /** @noinspection PhpUnused */
    protected function get_arguments(): array {
        $arguments = [];

        foreach ($this->__class->properties as $property) {
            if (!isset($property->attributes[ArgumentAttribute::class])) {
                continue;
            }

            /* @var ArgumentAttribute $attribute */
            $attribute = $property->attributes[ArgumentAttribute::class];

            $arguments[$property->name] = Option::new([
                'name' => $property->name,
                'mode' => $property->array
                    ? InputArgument::IS_ARRAY
                    : ($property->nullable
                        ? InputArgument::OPTIONAL
                        : InputArgument::REQUIRED
                    ),
                'description' => $property->description,
                'default' => $attribute->default,
            ]);
        }

        return $arguments;
    }

    protected function default(string $property): mixed {
        if (isset($this->__class->properties[$property]
            ->attributes[OptionAttribute::class]))
        {
            return $this->input->getOption($property);
        }

        if (isset($this->__class->properties[$property]
            ->attributes[ArgumentAttribute::class]))
        {
            return $this->input->getArgument($property);
        }

        return parent::default($property);
    }
}