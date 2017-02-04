<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */
 
namespace Larastart\Resource\Model;

interface ModelInterface
{
    /**
     * ModelInterface constructor.
     * @param array  $properties The model values.
     * @param string $name   The model name.
     */
    public function __construct(string $name, array $properties);

    /**
     * Retrieves the CamelCased name.
     * @return string
     */
    public function getName():string;

    public function getProperties():array;

    public function getTable():string;

    public function useTimestamps():bool;

    public function useSoftDeletes():bool;

    public function getColumns():ColumnCollection;

    /**
     * @return string|array
     */
    public function getHasOne();

    /**
     * @return string|array
     */
    public function getHasMany();

    /**
     * @return string|array
     */
    public function getBelongsTo();

    /**
     * @return string|array
     */
    public function getBelongsToMany();
}
