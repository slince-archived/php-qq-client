<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\ServiceRunner;

use Slince\PHPQQClient\Client;
use Slince\PHPQQClient\Console\Service\ServiceInterface;

abstract class ServiceRunner implements ServiceRunnerInterface
{
    /**
     * @var ServiceInterface[]
     */
    protected $services;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 添加多个服务到服务执行器
     * @param array $services
     */
    public function pushMany(array $services)
    {
        foreach ($services as $service) {
            $this->push($service);
        }
    }

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
        return $this->client;
    }
}