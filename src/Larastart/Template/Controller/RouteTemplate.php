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
    protected $flags = FILE_APPEND;

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

        // Create file header
        if (!file_exists($this->storagePath.DIRECTORY_SEPARATOR.$this->storageFileName)) {
            $this->save(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."web-header.php.template"));
        }

    }

    public function render(string $contents = ''):string
    {
        $contents     = $this->loadTemplate();
        $replacePairs = array(
            '!!className!!' => $this->getClassName($this->model),
            '!!modelName!!' => strtolower($this->model->getName()),
            'return return view(\'welcome\');' => 'return view(\'larastart-welcome\');'
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
