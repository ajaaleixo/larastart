<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Controller;

use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;

class RouteTemplate extends TemplateAbstract
{
    protected $model = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."web.php.template";
    protected $defaultStoragePath  = DIRECTORY_SEPARATOR."routes";

    public function __construct(ModelInterface $model, string $storagePath, string $templatePath = null)
    {
        $this->model           = $model;
        $this->templatePath    = $templatePath ?: $this->defaultTemplatePath;
        if (realpath($storagePath) === false) {
            $this->storagePath = getcwd().DIRECTORY_SEPARATOR.$storagePath.$this->defaultStoragePath;
        } else {
            $this->storagePath = realpath($storagePath).$this->defaultStoragePath;
        }
        $this->storageFileName = $this->makeFileName($model);
    }

    public function render():string
    {
        $contents     = $this->loadTemplate();
        $replacePairs = array(
            '!!className!!' => $this->getClassName($this->model),
            '!!modelName!!' => strtolower($this->model->getName()),
        );
        return strtr($contents, $replacePairs);
    }

    protected function makeFileName(ModelInterface $model)
    {
        return "web.php";
    }

    protected function getClassName(ModelInterface $model)
    {
        return sprintf("%sController", $model->getName());
    }
}
