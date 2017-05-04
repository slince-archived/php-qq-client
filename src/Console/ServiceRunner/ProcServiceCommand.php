<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\ServiceRunner;

use Slince\PHPQQClient\Console\Command\Command;
use Slince\PHPQQClient\Console\Service\ServiceInterface;
use Slince\PHPQQClient\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcServiceCommand extends Command
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    public function configure()
    {
        $this->setName('service-run')
            ->addArgument('serviceName', InputArgument::REQUIRED, '需要启动的服务名称');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceName = $input->getArgument('serviceName');
        $service = $this->getClient()->getService($serviceName);
        if (!$service) {
            throw new InvalidArgumentException('没有发现该服务');
        }
        $service->run(function($data) use ($output){
            $output->write(serialize($data));
        });
    }
}