<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\GroupMember;

class GroupMembersPanel extends Panel
{
    public function __construct($group, $groupDetail, $members)
    {
        parent::__construct([$group, $groupDetail, $members]);
    }

    protected function makeTable()
    {
        $headers = ['群友', '昵称', '性别', 'VIP等级', '角色'];
        $rows = $this->getData()[2]->map(function(GroupMember $member){
            return [
                $member->getCard() ?: $member->getNick(),
                $member->getNick(),
                $member->getGender(),
                $member->getVipLevel(),
                $member->getUin() == $this->getData()[1]->getOwner() ? '群主' : '普通成员'
            ];
        })->toArray();
        return [$headers, $rows];
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        list($headers, $rows) = $this->makeTable();
        $this->getStyle()->table($headers, $rows);
    }
}