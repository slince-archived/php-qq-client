<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\History;

use Slince\SmartQQ\Message\MessageInterface;

interface HistoryInterface
{
    /**
     * Get timestamp
     * @return int
     */
    public function getTimestamp();

    /**
     * @return MessageInterface
     */
    public function getMessage();
}