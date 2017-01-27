<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */
 
namespace Larastart\Template;

abstract class TemplateAbstract implements TemplateInterface
{
    protected $templatePath = "";
    protected $storagePath  = "";
    protected $storageFileName = "";

    /**
     * Loads the template content.
     *
     * @return string
     */
    protected function loadTemplate()
    {
        return file_get_contents($this->templatePath);
    }

    protected function save():bool
    {
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
        if (false === file_put_contents($this->storagePath.DIRECTORY_SEPARATOR.$this->storageFileName, $this->render())) {
            throw new \RuntimeException(sprintf(
                'Failed to write "%s" file',
                $this->storagePath
            ));
        }
        return true;
    }

    public function process():bool
    {
        return $this->save();
    }
}
