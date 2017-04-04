<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\ShowFriendsPanel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowFriendsCommand extends Command
{
    public function configure()
    {
        $this->setName('show-friends');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $friends = $this->getClient()->getFriends();
        $panel = $this->getApplication()->createPanel(ShowFriendsPanel::class, $friends);
        $panel->render();
    }
}