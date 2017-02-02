<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console\Command;

use Larastart\Resource\Model\ModelInterface;
use Larastart\Resource\ResourceFactory;
use Larastart\Template\Model\ModelTemplate;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModel extends AbstractCommand
{
    protected $resourceArgument = "resource";
    protected $outputPathArgument = "output";

    protected function configure()
    {
        $this->setName('make:model')
            ->setDescription('Generates Models from a resource file')
            ->addArgument($this->resourceArgument, InputArgument::REQUIRED, 'Path to the resource file')
            ->addArgument($this->outputPathArgument, InputArgument::OPTIONAL, "Output path/directory")
            ->setHelp(sprintf(
                '%sLarastart Generate Models for a given resource%s',
                PHP_EOL,
                PHP_EOL
            ));

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->info('Processing Models'));

        // Fetch argument and options
        $resourceArgument   = $input->getArgument($this->resourceArgument);
        $outputPathArgument = $input->getArgument($this->outputPathArgument) ?: getcwd();

        // Prepare Resource
        $resourceCollection = ResourceFactory::make($resourceArgument);
        $output->writeln(
            $this->info(sprintf("[INFO] Using resource(s) from: %s", $resourceArgument)),
            OutputInterface::VERBOSITY_VERBOSE
        );

        // Handle Model Template - write
        foreach ($resourceCollection as $resource) {
            /* @var $resource Resource */
            $this->writeModel($resource->getModel(), $outputPathArgument);
            $output->writeln($this->success(sprintf("Generated '%s's model", $resource->getName())));
        }
        $output->writeln($this->info('Finished Models'));
    }

    protected function writeModel(ModelInterface $model, $path = "")
    {
        $template = new ModelTemplate($model, $path);
        $template->process();
    }
}
