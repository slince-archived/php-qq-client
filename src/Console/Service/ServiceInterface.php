<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

use Slince\PHPQQClient\Client;

interface ServiceInterface
{
    public function getClient();

    public function setClient(Client $client);

    public function run($callback = null);

    public function getName();
}