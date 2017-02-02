<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Controller;

use Larastart\Resource\Model\ModelInterface;
use Larastart\Resource\ResourceCollection;
use Larastart\Template\TemplateAbstract;

class RouteTemplate extends TemplateAbstract
{
    protected $resourceCollection  = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."api.php.template";
    protected $defaultStoragePath  = DIRECTORY_SEPARATOR."routes";

    public function __construct(ResourceCollection $resourceCollection, string $storagePath, string $templatePath = null)
    {
        $this->resourceCollection  = $resourceCollection;
        $this->templatePath        = $templatePath ?: $this->defaultTemplatePath;
        if (realpath($storagePath) === false) {
            $this->storagePath = getcwd().DIRECTORY_SEPARATOR.$storagePath.$this->defaultStoragePath;
        } else {
            $this->storagePath = realpath($storagePath).$this->defaultStoragePath;
        }
        $this->storageFileName = $this->makeFileName();

        // Create file header
        if (!file_exists($this->storagePath.DIRECTORY_SEPARATOR.$this->storageFileName)) {
            $this->save(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."api-header.php.template"));
        }
    }

    public function render(string $contents = ''):string
    {
        // Group Resources by prefix
        $resourcesGroupedByPrefix = [];
        $middlewaresByPrefix = [];
        foreach($this->resourceCollection as $resource)
        {
            $prefix = strtolower($resource->getApi()->getPrefix());
            $resourcesGroupedByPrefix[$prefix][] = $resource;
            $middlewaresByPrefix[$prefix][] = $resource->getApi()->getMiddleware();
        }

        $groupTemplate = $this->loadTemplate();
        $groupContents = [];
        foreach($resourcesGroupedByPrefix as $prefix => $resources) {
            $replacePairs = [
                // Avoid writing api twice.. since we will write this on api.php route
                '!!prefix!!'      => $prefix === 'api' ? "" : $prefix,
                '!!middleware!!'  => implode("', '", $middlewaresByPrefix[$prefix]),
                '!!namespace!!'   => ucfirst($prefix),
                '!!routeGroups!!' => $this->getRouteGroups($resources),
            ];

            $groupContents[]= strtr($groupTemplate, $replacePairs);
        }

        return "<?php \n\n".implode("\n\n", $groupContents);
    }

    protected function getRouteGroups($collection)
    {
        // Prepare route Groups
        $routeGroups         = [];
        $routeGroupsTemplate = $this->loadTemplate(__DIR__.DIRECTORY_SEPARATOR."api-route-groups.template");
        /* @var $resource Resource */
        foreach ($collection as $resource) {
            $thisResourceTemplate = $routeGroupsTemplate;
            $replacePairs = [
                '!!className!!' => $this->getClassName($resource->getModel()),
                '!!modelName!!' => strtolower($resource->getModel()->getName()),
            ];
            $routeGroups []= strtr($thisResourceTemplate, $replacePairs);
        }

        return implode("\n\t\t\t", $routeGroups);
    }

    protected function makeFileName()
    {
        return "api.php";
    }

    protected function getClassName(ModelInterface $model)
    {
        return sprintf("%sController", $model->getName());
    }
}
