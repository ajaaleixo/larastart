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
    protected $hasOne;
    protected $hasMany;
    protected $belongsTo;
    protected $belongsToMany;

    const COLUMNS_PROPERTY = "columns";

    const TIMESTAMPS_PROPERTY  = "_timestamps";
    const TIMESTAMPS_TEMPLATE  = 'public $timestamps = $timestampsValue;';

    const TABLE_PROPERTY = "_table";
    const TABLE_TEMPLATE = 'protected $table = "$tableValue";';

    const RELATIONSHIP_hasOne_PROPERTY = "_hasOne";
    const RELATIONSHIP_hasMany_PROPERTY = "_hasMany";
    const RELATIONSHIP_belongsTo_PROPERTY = "_belongsTo";
    const RELATIONSHIP_belongsToMany_PROPERTY = "_belongsToMany";

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

        // Relationships
        $this->hasOne = ArrayUtils::getOptionalIndex($properties, self::RELATIONSHIP_hasOne_PROPERTY, false);
        $this->hasMany = ArrayUtils::getOptionalIndex($properties, self::RELATIONSHIP_hasMany_PROPERTY, false);
        $this->belongsTo = ArrayUtils::getOptionalIndex($properties, self::RELATIONSHIP_belongsTo_PROPERTY, false);
        $this->belongsToMany = ArrayUtils::getOptionalIndex($properties, self::RELATIONSHIP_belongsToMany_PROPERTY, false);
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

    /**
     * @return string|array
     */
    public function getHasOne()
    {
        return $this->hasOne;
    }

    /**
     * @return string|array
     */
    public function getHasMany()
    {
        return $this->hasMany;
    }

    /**
     * @return string|array
     */
    public function getBelongsTo()
    {
        return $this->belongsTo;
    }

    /**
     * @return string|array
     */
    public function getBelongsToMany()
    {
        return $this->belongsToMany;
    }
}
