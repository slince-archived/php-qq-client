<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\ProfilePanel;
use Slince\PHPQQClient\Exception\InvalidArgumentException;
use Slince\SmartQQ\Entity\Friend;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowFriendCommand extends Command
{
    public function configure()
    {
        $this->setName('show-friend');
        $this->addArgument('markname', InputArgument::REQUIRED, '好友昵称，备注');
        $this->setDescription('查看好友资料');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $markName = $input->getArgument('markname');
        $friend = $this->getClient()->getFriends()->filter(function(Friend $friend) use ($markName){
            return $friend->getMarkName() == $markName
                || $friend->getNick() == $markName;
        })->first();
        if (is_null($friend)) {
            throw new InvalidArgumentException(sprintf("没有发现好友[%s]", $markName));
        }
        $profile = $this->getClient()->getFriendDetail($friend);
        $panel = $this->getClient()->createPanel(ProfilePanel::class, [$profile]);
        $panel->render();
    }
}