<?php

namespace Osm\Framework\Console;

use Illuminate\Console\Command as BaseLaravelCommand;

/**
 * @property Command $command
 */
class LaravelCommand extends BaseLaravelCommand
{
    public function __construct(Command $command) {
        $this->command = $command;
        parent::__construct();
    }

    public function getName() {
        return $this->command->name;
    }

    public function getDescription() {
        return $this->command->description;
    }

    public function getOptions() {
        $result = [];

        foreach ($this->command->options_ as $option) {
            $result[] = [$option->name, $option->shortcut, $option->type, $option->description, $option->default];
        }

        return $result;
    }

    public function getArguments() {
        $result = [];

        foreach ($this->command->arguments_ as $argument) {
            $result[] = [$argument->name, $argument->type, $argument->description, $argument->default_];
        }

        return $result;
    }

    public function handle() {
        $this->command->input = $this->input;
        $this->command->output = $this->output;
        return $this->command->run() ?? 0;
    }
}