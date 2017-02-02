<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource;

interface ResourceCollectionInterface extends \Iterator
{
    /**
     * ResourceInterface constructor.
     * @param array $values Optional The resource array.
     */
    public function __construct(array $values);

    /**
     * Add Resource from array
     *
     * @param array $values
     * @return mixed
     */
    public function addResourcesFromArray(array $values);

    /**
     * Combines another collection
     *
     * @param ResourceCollection $collection
     * @return void
     */
    public function combine(ResourceCollection $collection);

    /**
     * Add parsed Resource
     *
     * @param ResourceInterface $resource
     * @return mixed
     */
    public function addResource(ResourceInterface $resource);
}
