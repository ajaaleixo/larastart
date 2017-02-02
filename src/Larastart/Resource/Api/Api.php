<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource\Api;

use Larastart\Utils\ArrayUtils;

class Api implements ApiInterface
{
    protected $prefix;
    protected $middleware;

    /**
     * Api constructor.
     *
     * @param array $api
     */
    public function __construct(array $api)
    {
        // Supposed to not end with slash
        $this->prefix = ArrayUtils::getOptionalIndex($api, "prefix", "api");
        // TODO Support multiple middleware
        $this->middleware = ArrayUtils::getOptionalIndex($api, "middleware");
    }

    /**
     * @inheritdoc
     */
    public function getPrefix():string
    {
        return $this->prefix;
    }

    /**
     * @inheritdoc
     */
    public function getMiddleware():string
    {
        return $this->middleware;
    }
}
