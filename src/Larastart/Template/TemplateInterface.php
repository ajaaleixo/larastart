<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template;

interface TemplateInterface
{
    public function process():bool;
    public function render(string $contents = ''):string;
}
