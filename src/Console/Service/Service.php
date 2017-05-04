<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

use Slince\PHPQQClient\Client;

abstract class Service implements ServiceInterface
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client;
        $this->initialize();
    }

    public function initialize()
    {
    }

    /**
     * 获取client
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
}