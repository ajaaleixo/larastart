<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource\Api;

interface ApiInterface
{
    /**
     * Returns the URI Prefix
     * @return string
     */
    public function getPrefix():string;

    /**
     * Returns the Middleware
     * @return string
     */
    public function getMiddleware():string;
}
