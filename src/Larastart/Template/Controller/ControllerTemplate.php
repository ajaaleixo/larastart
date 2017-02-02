<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Controller;

use Larastart\Resource\Api\ApiInterface;
use Larastart\Resource\Model\Column;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;

class ControllerTemplate extends TemplateAbstract
{
    protected $model = null;
    protected $api   = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."Controller.php.template";
    protected $defaultStoragePath  = DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Http".DIRECTORY_SEPARATOR."Controllers";

    public function __construct(ApiInterface $api, ModelInterface $model, string $storagePath, string $templatePath = null)
    {
        $this->api             = $api;
        $this->model           = $model;
        $this->templatePath    = $templatePath ?: $this->defaultTemplatePath;
        if (realpath($storagePath) === false) {
            $this->storagePath = getcwd().DIRECTORY_SEPARATOR.$storagePath.$this->defaultStoragePath;
        } else {
            $this->storagePath = realpath($storagePath).$this->defaultStoragePath;
        }

        // Check prefix Or Namespace
        if ($api->getPrefix()) {
            $this->storagePath .= DIRECTORY_SEPARATOR.ucfirst(strtolower($api->getPrefix()));
        }

        $this->storageFileName = $this->makeFileName($model);
    }

    public function render(string $contents = ''):string
    {
        $contents     = $contents ?: $this->loadTemplate();
        $replacePairs = array(
            '!!namespace!!' => $this->getNamespace($this->api),
            '!!className!!' => $this->getClassName($this->model),
            '!!modelName!!' => $this->model->getName(),
            '!!storeRequestName!!' => $this->getStoreRequestName($this->model),
            '!!modelStore!!' => $this->getModelStore($this->model),
        );
        return strtr($contents, $replacePairs);
    }

    protected function getNamespace(ApiInterface $api)
    {
        $prefix = "";
        if ($api->getPrefix()) {
            $prefix = '\\'.ucfirst(strtolower($api->getPrefix()));
        }
        return sprintf('namespace App\Http\Controllers'.$prefix.';');
    }

    protected function getStoreRequestName(ModelInterface $model)
    {
        return sprintf("Store%s", $model->getName());
    }

    protected function makeFileName(ModelInterface $model)
    {
        return sprintf("%sController.php", $model->getName());
    }

    protected function getClassName(ModelInterface $model)
    {
        return sprintf("%sController", $model->getName());
    }

    protected function getModelStore(ModelInterface $model)
    {
        $output = [];
        /* @var $column Column */
        foreach($model->getColumns() as $column) {
            // Avoid setting the "id" parameter
            if ($column->getName() !== 'id') {
                $output[] = '$model->'.$column->getName().' = $request->'.$column->getName().';';
            }
        }
        return implode("\n\t\t\t", $output);
    }
}
