<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Event;

use Slince\PHPQQClient\Constants;
use Slince\SmartQQ\Entity\DiscussMember;
use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\Entity\GroupMember;
use Slince\SmartQQ\Entity\User;
use Slince\SmartQQ\Message\Response\Message;

class ReceivedMessageEvent extends Event
{
    const EVENT_NAME = Constants::EVENT_RECEIVE_MESSAGE;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var GroupMember|DiscussMember|Friend
     */
    protected $sender;

    public function __construct(Message $message, User $sender, $subject)
    {
        $this->message = $message;
        $this->sender = $sender;
        parent::__construct(static::EVENT_NAME, $subject);
    }
}