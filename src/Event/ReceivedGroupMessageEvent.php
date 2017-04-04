<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Event;

use Slince\PHPQQClient\Constants;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\Entity\GroupMember;
use Slince\SmartQQ\Message\Response\GroupMessage;

class ReceivedGroupMessageEvent extends ReceivedMessageEvent
{
    const EVENT_NAME = Constants::EVENT_RECEIVE_GROUP_MESSAGE;
    /**
     * @var Group
     */
    protected $group;

    public function __construct(GroupMessage $message, Group $group, GroupMember $sender, $subject)
    {
        $this->group = $group;
        parent::__construct($message, $sender, $subject);
    }
}