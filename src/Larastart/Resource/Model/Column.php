<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource\Model;

use Larastart\Utils\ArrayUtils;

class Column
{
    protected $type;
    protected $name;
    protected $length;
    protected $isUnique;
    protected $isNullable;
    protected $isUnsigned;
    protected $index;

    public function __construct(array $col)
    {
        // TODO Validate types (increment, string, etc)
        $this->type = ArrayUtils::getMandatoryIndex($col, "type");
        $this->name = ArrayUtils::getMandatoryIndex($col, "name");
        $this->length = ArrayUtils::getOptionalIndex($col, "length");
        $this->isUnique = ArrayUtils::getOptionalIndex($col, "_unique", false);
        $this->isNullable = ArrayUtils::getOptionalIndex($col, "_nullable", false);
        $this->isUnsigned = ArrayUtils::getOptionalIndex($col, "_unsigned", false);
        $this->index = ArrayUtils::getOptionalIndex($col, "_index");
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->isUnique;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->isNullable;
    }

    /**
     * @return bool
     */
    public function isUnsigned()
    {
        return $this->isUnsigned;
    }

    /**
     * @return string|array
     */
    public function index()
    {
        return $this->index;
    }
}
