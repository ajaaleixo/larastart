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
     * @param string $originalFile The original file.
     * @param array  $values       The resource array.
     */
    public function __construct(string $originalFile, array $values);

    /**
     * Retrieves the original file from where the resource was mount.
     * @return string
     */
    public function getOriginalFile():string;
}
