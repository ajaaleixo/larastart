<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */
 
namespace Larastart\Resource;

use Symfony\Component\Finder\Finder;

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
        if (!is_dir($filePath)) {

            if(!file_exists($filePath)) {
                throw new \InvalidArgumentException(sprintf(
                    "The resource file '%s' does not exists",
                    $filePath
                ));
            } else {
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
        }

        if (is_dir($filePath)) {
            $finder = new Finder();
            $resourceCollection = new ResourceCollection();
            $files = $finder->files()->in($filePath)->path("/\\.json$/");
            foreach ($files as $file) {
                $resourceCollection->combine(self::makeFromJson($file->getRealPath()));
            }
            return $resourceCollection;
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
        return new ResourceCollection($content);
    }
}