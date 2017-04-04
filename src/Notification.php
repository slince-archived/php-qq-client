<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

class Notification
{
    const TYPE_RECEIVE_MESSAGE = 1;

    const TYPE_RECEIVE_GROUP_MESSAGE = 2;
    /**
     * @var int
     */
    protected $type;
}