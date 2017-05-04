<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\ServiceRunner;

use Slince\PHPQQClient\Console\Service\ServiceInterface;

interface ServiceRunnerInterface
{
    /**
     * The streams of service
     * @return array
     */
    public function getReadStreams();

    /**
     * Push a service to the runner
     * @param ServiceInterface $service
     */
    public function push(ServiceInterface $service);

    /**
     * Run all service
     */
    public function run();
}