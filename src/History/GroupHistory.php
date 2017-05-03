<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\Entity\GroupMember;
use Slince\SmartQQ\Message\MessageInterface;

class GroupHistory extends History
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var GroupMember
     */
    protected $groupMember;

    public function __construct($timestamp, MessageInterface $message, Group $group, GroupMember $groupMember)
    {
        $this->group = $group;
        $this->groupMember = $groupMember;
        parent::__construct($timestamp, $message);
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return GroupMember
     */
    public function getGroupMember()
    {
        return $this->groupMember;
    }
}