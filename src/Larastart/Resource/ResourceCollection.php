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

    public function __construct(array $values = [])
    {
        $this->rewind();
        if (!empty($values)) {
            $this->parseValues($values);
        }
    }

    public function combine(ResourceCollection $collection)
    {
        foreach($collection as $resource) {
            $this->addResource($resource);
        }
    }

    protected function parseValues(array $values)
    {
        foreach ($values as $resource) {
            $this->resources[] = new Resource($resource);
        }
    }

    public function addResourcesFromArray(array $values)
    {
        $this->parseValues($values);
    }

    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
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
