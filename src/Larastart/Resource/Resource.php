<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource;

use Larastart\Resource\Model\Model;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Utils\ArrayUtils;

class Resource implements ResourceInterface
{
    protected $name;
    protected $description;
    protected $model;

    const NAME_PROPERTY        = "name";
    const DESCRIPTION_PROPERTY = "description";
    const MODEL_PROPERTY       = "model";

    public function __construct(array $values)
    {
        if(empty($values)) {
            throw new \InvalidArgumentException(
                "Resource from file seems to be empty, please check or run 'make:resource' "
            );
        }

        // Check name - Mandatory
        $this->name = ArrayUtils::getMandatoryIndex($values, self::NAME_PROPERTY,
            "Resource missing property 'name'"
        );

        // Check model - Mandatory
        $this->model = new Model($this->name, ArrayUtils::getMandatoryIndex($values, self::MODEL_PROPERTY,
            "Resource missing property 'name'"
        ));

        // Check description - Optional
        $this->description = ArrayUtils::getOptionalIndex($values, self::DESCRIPTION_PROPERTY,
            sprintf("The %s resource", $this->name)
        );
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getDescription():string
    {
        return $this->description;
    }

    public function getModel():ModelInterface
    {
        return $this->model;
    }
}
 