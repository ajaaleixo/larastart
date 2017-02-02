<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;


class MakeAll extends AbstractCommand
{
    protected $resourceArgument = "resource";
    protected $outputPathArgument = "output";

    protected function configure()
    {
        $this->setName('make:all')
            ->setDescription('Wrapper to run all the other commands at once')
            ->addArgument($this->resourceArgument, InputArgument::REQUIRED, 'Path to the resource file or dir')
            ->addArgument($this->outputPathArgument, InputArgument::OPTIONAL, "Output path/directory")
            ->setHelp(sprintf(
                '%sLarastart Generate Api, Migrations & Models for a given resource%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->info('Processing All'));

        $resourceArgument   = $input->getArgument($this->resourceArgument);
        $outputPathArgument = $input->getArgument($this->outputPathArgument) ?: getcwd();

        $commandsToRun = [
            'make:api',
            'make:model',
            'make:migration',
        ];

        foreach($commandsToRun as $cmd) {
            $output->writeln($this->info('Running "'.$cmd.'"'));
            sleep(1);
            $this->runCommand($cmd, $output, $resourceArgument, $outputPathArgument);
        }

        $output->writeln($this->info('Finished All'));
    }

    /**
     * Runs a Larastart Command
     *
     * @param string $command
     * @param OutputInterface $output
     * @param string $resourceArg
     * @param string $outputArg
     * @return mixed
     */
    protected function runCommand(string $command, OutputInterface $output, string $resourceArg, string $outputArg)
    {
        $command = $this->getApplication()->find($command);
        $input = new ArrayInput([
            'command'  => $command,
            'resource' => $resourceArg,
            'output'   => $outputArg,
        ]);
        return $command->run($input, $output);
    }
}
