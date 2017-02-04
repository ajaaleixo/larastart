<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Utils;

class ArrayUtils
{
    /**
     * Retrieves a mandatory index existence and not empty.
     *
     * @param array $array
     * @param string $index
     * @param null $errorMessage
     * @return mixed
     */
    public static function getMandatoryIndex(array $array, string $index, $errorMessage = null)
    {
        $errorMessage ?: sprintf("Mandatory '%s' does not exists", $index);

        if (!isset($array[$index]) || empty($array[$index])) {
            throw new \InvalidArgumentException($errorMessage);
        }

        return $array[$index];
    }

    /**
     * Retrieves an optional index
     *
     * @param array $array
     * @param string $index
     * @param string $default
     * @return mixed
     */
    public static function getOptionalIndex(array $array, string $index, $default = "")
    {
        if (isset($array[$index])) {
            return $array[$index] ?: $default;
        }
        return $default;
    }
}
