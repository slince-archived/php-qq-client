<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Entity\DiscussMember;
use Slince\SmartQQ\Message\MessageInterface;

class DiscussHistory extends History
{
    /**
     * @var DiscussMember
     */
    protected $discussMember;

    public function __construct($timestamp, MessageInterface $message, DiscussMember $discussMember)
    {
        $this->discussMember = $discussMember;
        parent::__construct($timestamp, $message);
    }

    /**
     * @return DiscussMember
     */
    public function getDiscussMember()
    {
        return $this->discussMember;
    }
}