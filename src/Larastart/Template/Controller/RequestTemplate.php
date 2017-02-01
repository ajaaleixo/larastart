<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Controller;

use Larastart\Resource\Model\Column;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;

class RequestTemplate extends TemplateAbstract
{
    protected $model = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."Request.php.template";
    protected $defaultStoragePath  = DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Http".DIRECTORY_SEPARATOR."Requests";

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

    public function render(string $contents = ''):string
    {
        $contents     = $contents ?: $this->loadTemplate();
        $replacePairs = array(
            '!!className!!' => $this->getClassName($this->model),
            '!!modelRules!!' => $this->getRules($this->model),
        );
        return strtr($contents, $replacePairs);
    }

    protected function makeFileName(ModelInterface $model)
    {
        return sprintf("Store%s.php", $model->getName());
    }

    protected function getClassName(ModelInterface $model)
    {
        return sprintf("Store%s", $model->getName());
    }

    protected function getRules(ModelInterface $model)
    {
        $output = [];
        /* @var $col Column */
        foreach($model->getColumns() as $col) {
            $rules = $col->getRules();
            if (!empty($rules) && is_string($rules)) {
                $output[] = "'".strtolower($col->getName())."' => '".$col->getRules()."'";
            }
        }
        return implode(",\n\t\t\t", $output);
    }
}
