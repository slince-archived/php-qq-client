<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

use Slince\PHPQQClient\Console\Application;

class CommandUI
{
    /**
     * Application entry
     * @throws \Exception
     */
    static function main()
    {
        $application = new Application();
        $application->setAutoExit(true);
        $application->run();
    }
}