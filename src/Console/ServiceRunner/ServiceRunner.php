<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\ServiceRunner;

use Slince\PHPQQClient\Client;
use Slince\PHPQQClient\Console\ServiceInterface;

abstract class ServiceRunner implements ServiceRunnerInterface
{
    /**
     * @var ServiceInterface[]
     */
    protected $services;

    /**
     * {@inheritdoc}
     */
    public function push(ServiceInterface $service)
    {
        $this->services[] = $service;
    }

    /**
     * @return ServiceInterface[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return Client
     */
    public function getClient()
    {

    }
}