<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console\Command;

use Larastart\Resource\Api\ApiInterface;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Resource\Resource;
use Larastart\Resource\ResourceCollection;
use Larastart\Resource\ResourceFactory;
use Larastart\Template\Controller\ControllerTemplate;
use Larastart\Template\Controller\RequestTemplate;
use Larastart\Template\Controller\RouteTemplate;
use Larastart\Utils\FileUtils;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeApi extends AbstractCommand
{
    protected $resourceArgument = "resource";
    protected $outputPathArgument = "output";

    protected function configure()
    {
        $this->setName('make:api')
            ->setDescription('Generates API from a resource file')
            ->addArgument($this->resourceArgument, InputArgument::REQUIRED, 'Path to the resource file')
            ->addArgument($this->outputPathArgument, InputArgument::OPTIONAL, "Output path/directory")
            ->setHelp(sprintf(
                '%sLarastart Generate API for a given resource%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->info('Processing API'));

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
            $this->writeController($resource->getApi(), $resource->getModel(), $outputPathArgument);
            $this->writeRequest($resource->getModel(), $outputPathArgument);
            $output->writeln($this->success(sprintf("Generated '%s's API", $resource->getName())));
        }
        $this->writeRoute($resourceCollection, $outputPathArgument);
        $output->writeln($this->success("Generated Routes"));
        $this->copyView("larastart-welcome.blade.php.template", $outputPathArgument);

        $output->writeln($this->info('Finished API'));
    }

    protected function writeController(ApiInterface $api, ModelInterface $model, $path = "")
    {
        $template = new ControllerTemplate($api, $model, $path);
        $template->process();
    }

    protected function writeRequest(ModelInterface $model, $path = "")
    {
        $template = new RequestTemplate($model, $path);
        $template->process();
    }

    protected function writeRoute(ResourceCollection $resourceCollection, $path = "")
    {
        $template = new RouteTemplate($resourceCollection, $path);
        $template->process();
    }

    protected function copyView($viewName, $path = "")
    {
        $storagePath = "";
        $storageDir  = DIRECTORY_SEPARATOR."resources/views";
        if (realpath($path) === false) {
            $storagePath = getcwd().DIRECTORY_SEPARATOR.$path.$storageDir;
        } else {
            $storagePath = realpath($path).$storageDir;
        }
        FileUtils::createDirIfNotExists($storagePath);
        copy(__DIR__."/../../Template/View/".$viewName, $storagePath.DIRECTORY_SEPARATOR."welcome.blade.php");
        $this->success('Copied view ' . $viewName);
    }
}
