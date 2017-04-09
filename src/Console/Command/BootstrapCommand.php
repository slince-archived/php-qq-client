<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BootstrapCommand extends Command
{
    public function configure()
    {
        $this->setName('bootstrap')
            ->setDescription("PHP QQ Client");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        //获取登录凭证
        $this->getClient()->login();
    }
}