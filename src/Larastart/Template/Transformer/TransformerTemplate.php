<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Transformer;

use Larastart\Resource\Model\Column;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;

class TransformerTemplate extends TemplateAbstract
{
    protected $model = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."Transformer.php.template";
    protected $defaultStoragePath  = DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Http".DIRECTORY_SEPARATOR."Responses";

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
            '!!className!!'       => $this->getClassName($this->model),
            '!!modelName!!'       => $this->model->getName(),
            '!!columns!!'         => $this->getColumns($this->model),
        );
        return strtr($contents, $replacePairs);
    }

    protected function makeFileName(ModelInterface $model)
    {
        return sprintf("%sTransformer.php", $model->getName());
    }

    protected function getClassName(ModelInterface $model):string
    {
        return $model->getName()."Transformer";
    }
    protected function getColumns(ModelInterface $model)
    {
        $output = [];
        foreach ($model->getColumns() as $column) {
            /* @var $column Column */
            $parameter = strtolower($column->getName());
            $output[] = '"'.$parameter.'" => $model->'.$parameter;
        }
        return implode(",\n\t\t\t", $output);
    }
}
