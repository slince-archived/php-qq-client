<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

final class Constants
{
    /**
     * loin event name
     * @var string
     */
    const EVENT_LOGIN = 'login';

    /**
     * 收到消息
     * @var string
     */
    const EVENT_RECEIVE_MESSAGE = 'receivedMessage';

    /**
     * 收到群消息
     * @var string
     */
    const EVENT_RECEIVE_GROUP_MESSAGE = 'receivedGroupMessage';

    /**
     * 收到讨论组消息
     * @var string
     */
    const EVENT_RECEIVE_DISCUSS_MESSAGE = 'receivedDiscussMessage';

    const CACHE_FRIENDS = '_friends';

    const CACHE_GROUPS = '_groups';

    const CACHE_DISCUSSES = '_discusses';
}