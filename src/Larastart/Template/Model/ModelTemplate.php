<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Model;

use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;

class ModelTemplate extends TemplateAbstract
{
    protected $model = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."Model.php.template";
    protected $defaultStoragePath = DIRECTORY_SEPARATOR."App";

    public function __construct(ModelInterface $model, string $storagePath, string $templatePath = null)
    {
        $this->model           = $model;
        $this->templatePath    = $templatePath ?: $this->defaultTemplatePath;
        if (realpath($storagePath) === false) {
            $this->storagePath = getcwd().DIRECTORY_SEPARATOR.$storagePath.$this->defaultStoragePath;
        } else {
            $this->storagePath = realpath($storagePath).$this->defaultStoragePath;
        }
        $this->storageFileName = $model->getName().".php";
    }

    public function render():string
    {
        $contents     = $this->loadTemplate();
        $replacePairs = array(
            '!!traits!!'          => $this->getTraits($this->model),
            '!!className!!'       => $this->model->getName(),
            '!!tableValue!!'      => strtolower($this->model->getTable()),
            '!!timestampsValue!!' => $this->model->useTimestamps() ? 'true' : 'false',
            '!!dates!!'           => $this->getDates($this->model),
        );
        return strtr($contents, $replacePairs);
    }

    protected function getTraits(ModelInterface $model)
    {
        $traits = [];
        if ($model->useSoftDeletes()) {
            $traits[]= 'use \Illuminate\Database\Eloquent\SoftDeletes;';
        }
        return implode("\n", $traits);
    }

    protected function getDates(ModelInterface $model)
    {
        $output = [];
        if ($model->useSoftDeletes()) {
            $output[]= 'deleted_at';
        }
        if ($model->useTimestamps()) {
            $output[]= 'created_at';
            $output[]= 'updated_at';
        }
        return 'protected $dates = ["'.implode("', '", $output).'"];';
    }
}
