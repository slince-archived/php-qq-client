<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Message\MessageInterface;

class History implements HistoryInterface
{
    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var MessageInterface
     */
    protected $message;

    public function __construct($timestamp, MessageInterface $message)
    {
        $this->timestamp = $timestamp;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }
}