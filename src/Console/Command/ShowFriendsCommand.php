<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\ShowFriendsPanel;
use Slince\SmartQQ\Entity\Friend;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowFriendsCommand extends Command
{
    public function configure()
    {
        $this->setName('show-friends');
        $this->addOption('categories', null, InputOption::VALUE_OPTIONAL, '按分类筛选，多个分类使用点号点号分离');
        $this->addOption('names', null, InputOption::VALUE_OPTIONAL, '按昵称或者备注筛选，多个值使用点号分离');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $friends = $this->getClient()->getFriends();
        //按照分类筛选
        if ($categories = $input->getOption('categories')) {
            $categories = array_filter(explode(',', $categories));
            $friends = $friends->filter(function(Friend $friend) use ($categories){
                return in_array($friend->getCategory()->getName(), $categories);
            });
        }
        //按照昵称或者备注筛选
        if ($names = $input->getOption('names')) {
            $names = array_filter(explode(',', $names));
            $friends = $friends->filter(function(Friend $friend) use ($names){
                return in_array($friend->getNick(), $names)
                    || in_array($friend->getMarkName(), $names);
            });
        }
        $panel = $this->getClient()->createPanel(ShowFriendsPanel::class, [$friends]);
        $panel->render();
    }
}