<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

use Symfony\Component\Console\Input\ArgvInput;

class CommandUI
{
    /**
     * Application entry
     * @throws \Exception
     */
    static function main()
    {
        $input = new ArgvInput();
        if (true === $input->hasParameterOption(array('--config'), true)) {
            $configuration = Configuration::fromConfigFile($input->getParameterOption('--config'));
        } else {
            $configuration = new Configuration();
        }
        $application = new Client($configuration);
        $application->setAutoExit(true);
        $application->run($input);
    }
}