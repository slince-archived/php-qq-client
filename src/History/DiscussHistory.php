<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\DiscussMember;
use Slince\SmartQQ\Message\MessageInterface;

class DiscussHistory extends History
{
    /**
     * @var Discuss
     */
    protected $discuss;

    /**
     * @var DiscussMember
     */
    protected $discussMember;

    public function __construct($timestamp, MessageInterface $message, Discuss $discuss, DiscussMember $discussMember)
    {
        $this->discuss = $discuss;
        $this->discussMember = $discussMember;
        parent::__construct($timestamp, $message);
    }

    /**
     * @return Discuss
     */
    public function getDiscuss()
    {
        return $this->discuss;
    }

    /**
     * @return DiscussMember
     */
    public function getDiscussMember()
    {
        return $this->discussMember;
    }
}