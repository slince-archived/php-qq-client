<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DiscussChatCommand extends ChatCommand
{
    public function configure()
    {
        $this->setName('discuss-chat');
        $this->addArgument('markname', InputArgument::REQUIRED, '讨论组名称，备注');
        $this->setDescription('发起讨论组聊天');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}