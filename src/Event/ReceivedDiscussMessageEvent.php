<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Event;

use Slince\PHPQQClient\Constants;
use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\DiscussMember;
use Slince\SmartQQ\Message\Response\DiscussMessage;

class ReceivedDiscussMessageEvent extends ReceivedMessageEvent
{
    const EVENT_NAME = Constants::EVENT_RECEIVE_DISCUSS_MESSAGE;
    /**
     * @var Discuss
     */
    protected $discuss;

    public function __construct(DiscussMessage $message, Discuss $discuss, DiscussMember $sender, $subject)
    {
        $this->discuss = $discuss;
        parent::__construct($message, $sender, $subject);
    }
}