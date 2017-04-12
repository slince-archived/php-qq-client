<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\ProfilePanel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowMeCommand extends Command
{
    public function configure()
    {
        $this->setName('show-me');
        $this->setDescription('显示个人资料');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $profile = $this->getClient()->getCurrentUserProfile();
        $panel = $this->getClient()->createPanel(ProfilePanel::class, [$profile]);
        $panel->render();
    }
}