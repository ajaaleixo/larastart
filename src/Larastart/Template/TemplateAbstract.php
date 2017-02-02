<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */
 
namespace Larastart\Template;

use Larastart\Utils\FileUtils;

abstract class TemplateAbstract implements TemplateInterface
{
    protected $templatePath = "";
    protected $storagePath  = "";
    protected $storageFileName = "";
    protected $flags = null;

    /**
     * Loads the template content.
     *
     * @param string $template Optional
     * @return string
     */
    protected function loadTemplate($template = "")
    {
        return file_get_contents($template ? $template : $this->templatePath);
    }

    protected function save(string $content = ''):bool
    {
        $this->checkDirectory($this->storagePath);
        if (false === file_put_contents(
                $this->checkFile($this->storagePath.DIRECTORY_SEPARATOR.$this->storageFileName),
                $content ?: $this->render(),
                $this->flags)) {
            throw new \RuntimeException(sprintf(
                'Failed to write/append content to "%s" file',
                $this->storagePath
            ));
        }
        return true;
    }

    protected function checkDirectory(string $storagePath)
    {
        FileUtils::createDirIfNotExists($storagePath);
    }

    protected function checkFile(string $filePath)
    {
        // Overwrite to decide what do to if file exists
        if (file_exists($filePath)) {
            // By default, ignore, overwrite file
        }
        return $filePath;
    }

    public function process():bool
    {
        return $this->save();
    }
}
