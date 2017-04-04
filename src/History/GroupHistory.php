<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Entity\GroupMember;
use Slince\SmartQQ\Message\MessageInterface;

class GroupHistory extends History
{
    /**
     * @var GroupMember
     */
    protected $groupMember;

    public function __construct($timestamp, MessageInterface $message, GroupMember $groupMember)
    {
        $this->groupMember = $groupMember;
        parent::__construct($timestamp, $message);
    }

    /**
     * @return GroupMember
     */
    public function getGroupMember()
    {
        return $this->groupMember;
    }
}