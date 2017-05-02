<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\FriendsPanel;
use Slince\PHPQQClient\Console\Panel\GroupMembersPanel;
use Slince\PHPQQClient\Exception\InvalidArgumentException;
use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\Entity\GroupMember;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListGroupMembersCommand extends Command
{
    public function configure()
    {
        $this->setName('list-group-members');
        $this->addArgument('name', InputArgument::REQUIRED, '群名称或者群备注');
        $this->addOption('names', null, InputOption::VALUE_OPTIONAL, '按昵称或者群名片筛选，多个值使用点号分离');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $group = $this->getClient()->getGroups()->filter(function(Group $group) use($name){
            return $group->getName()  == $name;
        })->first();
        if (!$group) {
            throw new InvalidArgumentException(sprintf("没有发现群[%s]", $name));
        }
        $groupDetail  = $this->getClient()->getGroupsDetail($group);
        $members = $groupDetail->getMembers();
        //按照昵称或者备注筛选
        if ($names = $input->getOption('names')) {
            $names = array_filter(explode(',', $names));
            $members = $members->filter(function(GroupMember $member) use ($names){
                return in_array($member->getNick(), $names)
                    || in_array($member->getCard(), $names);
            });
        }
        $panel = new GroupMembersPanel($group, $groupDetail, $members);
        $panel->render();
    }
}