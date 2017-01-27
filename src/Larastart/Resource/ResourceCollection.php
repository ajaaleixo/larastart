<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */
 
namespace Larastart\Resource;

class ResourceCollection implements ResourceCollectionInterface
{
    protected $resources = [];
    protected $originalFile;
    protected $index;

    public function __construct(string $originalFile, array $values)
    {
        $this->originalFile = $originalFile;
        $this->rewind();
        $this->parseValues($values);
    }

    protected function parseValues(array $values)
    {
        foreach ($values as $i => $resource) {
            $this->resources[$i] = new Resource($resource);
        }
    }

    public function getOriginalFile():string
    {
        return $this->originalFile;
    }

    public function current()
    {
        return $this->resources[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->resources[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }
}
