<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Client;
use Slince\PHPQQClient\Exception\LogicException;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Service extends BaseCommand
{
    /**
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->getClient()->getInput();
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->getClient()->getOutput();
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
        return $application;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->process();
    }

    /**
     * 运行服务
     */
    abstract public function process();
}