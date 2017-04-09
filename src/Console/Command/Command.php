<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Client;
use Slince\PHPQQClient\Console\Application;
use Slince\PHPQQClient\Exception\LogicException;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->getApplication()->getInput();
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->getApplication()->getInput();
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return parent::getApplication();
    }

    /**
     * 获取client
     * @return Client
     */
    public function getClient()
    {
        $application = $this->getApplication();
        if (is_null($application)) {
            throw new LogicException("You should set a application for the command");
        }
        return $application->getClient();
    }
}