<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console\Command;

use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{
    public function info($text)
    {
        return $text;
    }

    public function success($text)
    {
        return "<info>".$text."</info>";
    }
}
