<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console\Command;

use Larastart\Resource\Model\ModelInterface;
use Larastart\Resource\ResourceFactory;
use Larastart\Template\Controller\ControllerTemplate;
use Larastart\Template\Controller\RouteTemplate;
use Larastart\Utils\FileUtils;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeController extends AbstractCommand
{
    protected $resourceArgument = "resource";
    protected $outputPathArgument = "output";

    protected function configure()
    {
        $this->setName('make:controller')
            ->setDescription('Generates Controllers from a resource file')
            ->addArgument($this->resourceArgument, InputArgument::REQUIRED, 'Path to the resource file')
            ->addArgument($this->outputPathArgument, InputArgument::OPTIONAL, "Output path/directory")
            ->setHelp(sprintf(
                '%sLarastart Generate Controllers for a given resource%s',
                PHP_EOL,
                PHP_EOL
            ));

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->info('Processing Controllers'));

        // Fetch argument and options
        $resourceArgument   = $input->getArgument($this->resourceArgument);
        $outputPathArgument = $input->getArgument($this->outputPathArgument) ?: getcwd();

        // Prepare Resource
        $resourceCollection = ResourceFactory::make($resourceArgument);
        $output->writeln(
            $this->info(sprintf("[INFO] Using resource(s) from: %s", $resourceCollection->getOriginalFile())),
            OutputInterface::VERBOSITY_VERBOSE
        );

        // Handle Model Template - write
        foreach ($resourceCollection as $resource) {
            /* @var $resource Resource */
            $this->writeController($resource->getModel(), $outputPathArgument);
            $this->appendRoute($resource->getModel(), $outputPathArgument);
            $output->writeln($this->success(sprintf("Generated '%s's controller", $resource->getName())));
        }
        $this->copyView("larastart-welcome.blade.php.template", $outputPathArgument);
        $output->writeln($this->info('Finished'));
    }

    protected function writeController(ModelInterface $model, $path = "")
    {
        $template = new ControllerTemplate($model, $path);
        $template->process();
    }

    protected function appendRoute(ModelInterface $model, $path = "")
    {
        $template = new RouteTemplate($model, $path);
        $template->process();
    }

    protected function copyView($viewName , $path = "")
    {
        $storagePath = "";
        $storageDir  = DIRECTORY_SEPARATOR."resources/views";
        if (realpath($path) === false) {
            $storagePath = getcwd().DIRECTORY_SEPARATOR.$path.$storageDir;
        } else {
            $storagePath = realpath($path).$storageDir;
        }
        FileUtils::createDirIfNotExists($storagePath);
        copy(__DIR__."/../../Template/View/".$viewName, $storagePath.DIRECTORY_SEPARATOR.str_replace(".template", "", $viewName));
        $this->success('Copied view ' . $viewName);
    }
}
