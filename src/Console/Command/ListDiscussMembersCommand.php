<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\FriendsPanel;
use Slince\PHPQQClient\Console\Panel\DiscussMembersPanel;
use Slince\PHPQQClient\Exception\InvalidArgumentException;
use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\DiscussMember;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListDiscussMembersCommand extends Command
{
    public function configure()
    {
        $this->setName('list-discuss-members');
        $this->addArgument('name', InputArgument::REQUIRED, '讨论组名称');
        $this->addOption('names', null, InputOption::VALUE_OPTIONAL, '按昵称筛选，多个值使用点号分离');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $discuss = $this->getClient()->getDiscusses()->filter(function(Discuss $discuss) use($name){
            return $discuss->getName()  == $name;
        })->first();
        if (!$discuss) {
            throw new InvalidArgumentException(sprintf("没有发现讨论组[%s]", $name));
        }
        $discussDetail = $this->getClient()->getDiscussDetail($discuss);
        $members = $discussDetail->getMembers();
        //按照昵称或者备注筛选
        if ($names = $input->getOption('names')) {
            $names = array_filter(explode(',', $names));
            $members = $members->filter(function(DiscussMember $member) use ($names){
                return in_array($member->getNick(), $names);
            });
        }
        $panel = new DiscussMembersPanel($discuss, $discussDetail, $members);
        $panel->render();
    }
}