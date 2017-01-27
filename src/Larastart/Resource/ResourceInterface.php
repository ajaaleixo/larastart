<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource;

use Larastart\Resource\Model\ModelInterface;

interface ResourceInterface
{
    /**
     * ResourceInterface constructor.
     * @param array  $values       The resource array.
     */
    public function __construct(array $values);

    /**
     * Retrieves the resource name.
     * @return string
     */
    public function getName():string;

    /**
     * Retrieves the resource description.
     * @return string
     */
    public function getDescription():string;

    /**
     * Retrieves the resource model.
     * @return ModelInterface
     */
    public function getModel():ModelInterface;
}
