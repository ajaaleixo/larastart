<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Console;

use Larastart\Console\Command\MakeController;
use Larastart\Console\Command\MakeModel;
use Larastart\Console\Command\MakeMigration;
use Symfony\Component\Console\Application;

class LarastartApplication extends Application
{
    /**
     * Init Console App.
     *
     * @param string $version The Application Version
     */
    public function __construct($version = '1.0.0')
    {
        parent::__construct('Larastart by Andre Aleixo - http://larastart.io', $version);

        $this->addCommands([
            new MakeModel(),
            new MakeMigration(),
            new MakeController(),
        ]);
    }
}
