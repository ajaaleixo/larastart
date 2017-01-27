<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource\Model;

use Larastart\Utils\ArrayUtils;

class Model implements ModelInterface
{
    protected $name;
    protected $columns;
    protected $properties;
    protected $table;
    protected $timestamps;
    protected $useSoftDeletes;

    const COLUMNS_PROPERTY = "columns";

    const TIMESTAMPS_PROPERTY  = "_timestamps";
    const TIMESTAMPS_TEMPLATE  = 'public $timestamps = $timestampsValue;';

    const TABLE_PROPERTY = "_table";
    const TABLE_TEMPLATE = 'protected $table = "$tableValue";';

    /**
     * Model constructor.
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, array $properties)
    {
        $this->name = $name;
        if(empty($properties)) {
            throw new \InvalidArgumentException(sprintf(
                "Properties from Model '%s' are empty, please provide at least one", $name
            ));
        }

        // Columns
        $this->columns = new ColumnCollection(ArrayUtils::getMandatoryIndex($properties, self::COLUMNS_PROPERTY));

        // Table property
        $this->table = ArrayUtils::getOptionalIndex($properties, self::TABLE_PROPERTY, $name);

        // Timestamps property
        $this->timestamps = ArrayUtils::getOptionalIndex($properties, self::TIMESTAMPS_PROPERTY, true);

        // Softdeletes property
        $this->useSoftDeletes = ArrayUtils::getOptionalIndex($properties, self::TIMESTAMPS_PROPERTY, false);
    }

    public function getName():string
    {
        return ucfirst(strtolower($this->name));
    }

    public function getProperties():array
    {
        return $this->properties;
    }

    public function getTable():string
    {
        return strtolower($this->table);
    }

    public function useTimestamps():bool
    {
        return $this->timestamps;
    }

    public function getColumns():ColumnCollection
    {
        return $this->columns;
    }

    public function useSoftDeletes():bool
    {
        return $this->useSoftDeletes;
    }
}
