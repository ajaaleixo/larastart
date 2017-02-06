<?php

namespace Larastart\Template\Seed;

use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;

class SeedTemplate extends TemplateAbstract
{
    protected $source = null;
    protected $table = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."Seed.php.template";
    protected $defaultStoragePath = DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."seeds";
    protected $suffix = 'TableSeeder';

    public function __construct($table, $source, $storagePath, string $templatePath = null)
    {
        $this->source          = $source;
        $this->table           = ucfirst($table);
        $this->templatePath    = $templatePath ?: $this->defaultTemplatePath;
        if (realpath($storagePath) === false) {
            $this->storagePath = getcwd().DIRECTORY_SEPARATOR.$storagePath.$this->defaultStoragePath;
        } else {
            $this->storagePath = realpath($storagePath).$this->defaultStoragePath;
        }
        $this->storageFileName = $this->table.$this->suffix.".php";
    }

    public function render(string $contents = ''):string
    {
        $contents = $contents ?: $this->loadTemplate();

        $replacePairs = array(
            '!!className!!' => $this->table.$this->suffix,
            '!!tableName!!' => strtolower($this->table),
            '!!data!!'      => var_export($this->getSource(), true),
        );

        return strtr($contents, $replacePairs);
    }

    protected function getSource()
    {
        foreach ($this->source as $source) {
            $arr[] = $source;
        }

        return $arr;
    }
}
