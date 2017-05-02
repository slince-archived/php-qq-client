<?php
/**
 * Created by PhpStorm.
 * User: ZMoffice
 * Date: 2017/5/2
 * Time: 17:56
 */

namespace Slince\PHPQQClient\Console\Command;

use Slince\Event\SubscriberInterface;
use Slince\PHPQQClient\Console\Service\MessageService;
use Slince\PHPQQClient\Constants;
use Slince\PHPQQClient\Event\ReceivedMessageEvent;

class MessageSubscriber implements SubscriberInterface
{
    protected $notificationService;

    public function __construct(MessageService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return [
            Constants::EVENT_RECEIVE_MESSAGE => 'onReceiveMessage',
            Constants::EVENT_RECEIVE_GROUP_MESSAGE => 'onReceiveGroupMessage',
            Constants::EVENT_RECEIVE_DISCUSS_MESSAGE => 'onReceiveDiscussMessage',
        ];
    }

    public function onReceiveMessage(ReceivedMessageEvent $event)
    {

    }
}