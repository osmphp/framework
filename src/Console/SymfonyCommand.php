<?php

declare(strict_types=1);

namespace Osm\Framework\Console;

use Osm\Framework\Console\Exceptions\ConsoleError;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyCommand extends BaseCommand
{
    /**
     * @var Command
     */
    protected Command $command;

    public function __construct(Command $command) {
        $this->command = $command;
        parent::__construct($command->name);
    }

    protected function configure() {
        foreach ($this->command->options as $option) {
            $this->addOption($option->name, $option->shortcut,
                $option->mode, $option->description ?? '',
                $option->default);
        }

        foreach ($this->command->arguments as $argument) {
            $this->addArgument($argument->name, $argument->mode,
            $argument->description ?? '', $argument->default);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $this->command->input = $input;
        $this->command->output = $output;

        try {
            $this->command->run();

            // 0 means command ran successfully, any other return code
            // means that something went wrong
            return 0;
        }
        catch (ConsoleError $e) {
            if ($e->getMessage()) {
                $output->writeln($e->getMessage());
            }
            return $e->getCode();
        }
    }
}