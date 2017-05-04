<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\ServiceRunner;

use Slince\PHPQQClient\Console\Service\ServiceInterface;
use Slince\PHPQQClient\Exception\RuntimeException;

class ProcServiceRunner extends ServiceRunner
{
    protected $streams = [];

    protected $running = false;

    /**
     * 执行所有服务
     */
    public function run()
    {
        foreach ($this->getServices() as $service) {
            $this->runService($service);
        }
        $this->running = true;
    }

    public function push(ServiceInterface $service)
    {
        parent::push($service);
        if ($this->running) {
            $this->runService($service);
        }
    }

    /**
     * 执行服务
     * @param ServiceInterface $service
     */
    protected function runService(ServiceInterface $service)
    {
        $command = sprintf("php bin/phpqq service-run %s", $service->getName());
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => STDERR // 标准错误，写入到一个文件
//            2 => array("file", "/tmp/error-output.txt", "a") // 标准错误，写入到一个文件
        );
        $process = proc_open($command, $descriptorspec, $pipes,
            $this->getClient()->getConfiguration()->getBasePath());
        if (is_resource($process)) {
            $this->streams[$service->getName()] = $pipes;
        }
        throw new RuntimeException(sprintf("服务%s无法启动", $service->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function getReadStreams()
    {
        return array_map(function($pipes){
            return $pipes[1];
        }, $this->streams);
    }
}