<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\GroupsPanel;
use Slince\SmartQQ\Entity\Group;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListGroupsCommand extends Command
{
    public function configure()
    {
        $this->setName('list-groups');
        $this->addOption('names', null, InputOption::VALUE_OPTIONAL, '按群名称或者备注筛选，多个值使用点号分离');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $groups = $this->getClient()->getGroups();
        //按照昵称或者备注筛选
        if ($names = $input->getOption('names')) {
            $names = array_filter(explode(',', $names));
            $groups = $groups->filter(function(Group $group) use ($names){
                return in_array($group->getName(), $names)
                    || in_array($group->getMarkName(), $names);
            });
        }
        $panel = $this->getClient()->createPanel(GroupsPanel::class, [$groups, []]);
        $panel->render();
    }
}