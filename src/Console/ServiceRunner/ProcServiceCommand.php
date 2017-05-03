<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\ServiceRunner;

use Slince\PHPQQClient\Console\Command\Command;
use Slince\PHPQQClient\Console\ServiceInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcServiceCommand extends Command
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    public function __construct(ServiceInterface $service)
    {
        $this->service = $service;
        parent::__construct(null);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->service->run();
    }
}