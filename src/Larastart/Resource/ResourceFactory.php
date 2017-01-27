<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */
 
namespace Larastart\Resource;

class ResourceFactory
{
    /**
     * Makes a new Resource from a filePath given a specific format.
     *
     * @param $filePath The filePath
     * @throws \InvalidArgumentException
     * @return ResourceCollectionInterface
     */
    public static function make($filePath):ResourceCollectionInterface
    {
        // Check file and dir
        // TODO Add option to load all resource files from a folder if resourceFile is a dir
        if (!file_exists($filePath) || is_dir($filePath)) {
            throw new \InvalidArgumentException(sprintf(
                "The resource file '%s' does not exists",
                $filePath
            ));
        }

        // TODO Add support to php and yml format
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        switch (strtolower($extension)) {
            case 'json':
                return self::makeFromJson($filePath);
            case 'php':
            case 'yml':
            default:
                throw new \InvalidArgumentException(sprintf(
                    "The resource file '%s' is not parseable. Please use json format.",
                    $filePath
                ));
        }
    }

    /**
     * Create a Resource instance from a json file.
     *
     * @param  string $file The json File
     * @throws \RuntimeException
     * @return ResourceInterface
     */
    public static function makeFromJson($file):ResourceCollectionInterface
    {
        $content = json_decode(file_get_contents($file), true);
        if (!is_array($content)) {
            throw new \RuntimeException(sprintf("File '%s' must be in json format", $file));
        }
        return new ResourceCollection($file, $content);
    }
}