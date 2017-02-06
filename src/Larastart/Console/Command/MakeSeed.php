<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console\Command;

use League\Csv\Reader;
use Larastart\Resource\Model\SeedInterface;
use Larastart\Template\Seed\SeedTemplate;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeSeed extends AbstractCommand
{
    protected $tableArgument      = "table";
    protected $sourceArgument     = "source";
    protected $outputPathArgument = "output";

    protected function configure()
    {
        $this->setName('make:seed')
            ->setDescription('Generates Seeds from the spreadsheet file')
            ->addArgument($this->tableArgument, InputArgument::REQUIRED, 'Table name')
            ->addArgument($this->sourceArgument, InputArgument::REQUIRED, 'Path to the spreadsheet file')
            ->addArgument($this->outputPathArgument, InputArgument::OPTIONAL, "Output2 path/directory")
            ->setHelp(sprintf(
                '%sLarastart Generate Seeds for a given source%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->info('Processing Seeds'));

        // Fetch argument and options
        $tableArgument      = $input->getArgument($this->tableArgument);
        $sourceArgument     = $input->getArgument($this->sourceArgument);
        $outputPathArgument = $input->getArgument($this->outputPathArgument) ?: getcwd();

        // Prepare Resource
        $sourceCollection = Reader::createFromPath($sourceArgument)->fetchAssoc();
        $output->writeln(
            $this->info(sprintf("[INFO] Using seed(s) from: %s", $tableArgument)),
            OutputInterface::VERBOSITY_VERBOSE
        );

        $this->writeSeed($tableArgument, $sourceCollection, $outputPathArgument);
        $output->writeln($this->success(sprintf("Generated '%s's seed", $tableArgument)));

        $output->writeln($this->info('Finished'));
    }

    protected function writeSeed($table, $model, $path = "")
    {
        $template = new SeedTemplate($table, $model, $path);
        $template->process();
    }
}
