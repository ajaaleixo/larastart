<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Utils;

class FileUtils
{
    /**
     * Create dir if not exists
     *
     * @param $path
     */
    public static function createDirIfNotExists($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
