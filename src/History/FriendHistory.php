<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Message\MessageInterface;
use Slince\SmartQQ\Entity\Friend;

class FriendHistory extends History
{
    /**
     * @var Friend
     */
    protected $friend;

    public function __construct($timestamp, MessageInterface $message, Friend $friend)
    {
        $this->friend = $friend;
        parent::__construct($timestamp, $message);
    }

    /**
     * @return Friend
     */
    public function getFriend()
    {
        return $this->friend;
    }
}