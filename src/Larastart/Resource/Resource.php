<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource;

use Larastart\Resource\Model\Model;
use Larastart\Resource\Api\Api;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Resource\Api\ApiInterface;
use Larastart\Utils\ArrayUtils;

class Resource implements ResourceInterface
{
    protected $name;
    protected $description;
    protected $model;
    protected $api;

    public function __construct(array $values)
    {
        if (empty($values)) {
            throw new \InvalidArgumentException(
                "Resource from file seems to be empty, please check or run 'make:resource' "
            );
        }

        // Check URI prefix - Mandatory
        $this->api = new Api(ArrayUtils::getMandatoryIndex($values, "api",
            sprintf("The %s resource", $this->name)
        ));

        // Check name - Mandatory
        $this->name = ArrayUtils::getMandatoryIndex($values, "name",
            "Resource missing property 'name'"
        );
        // Make name CamelCase
        $this->name = str_replace(" ", "", ucwords(str_replace(["-", "_"], " ", strtolower($this->name))));

        // Check model - Mandatory
        $this->model = new Model($this->name, ArrayUtils::getMandatoryIndex($values, "model",
            "Resource missing property 'name'"
        ));

        // Check description - Optional
        $this->description = ArrayUtils::getOptionalIndex($values, "description",
            sprintf("The %s resource", $this->name)
        );
    }

    /**
     * @inheritdoc
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getDescription():string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function getModel():ModelInterface
    {
        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function getApi():ApiInterface
    {
        return $this->api;
    }
}
