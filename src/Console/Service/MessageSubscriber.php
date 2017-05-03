<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

use Slince\Event\SubscriberInterface;
use Slince\PHPQQClient\Constants;
use Slince\PHPQQClient\Event\ReceivedDiscussMessageEvent;
use Slince\PHPQQClient\Event\ReceivedGroupMessageEvent;
use Slince\PHPQQClient\Event\ReceivedMessageEvent;
use Slince\PHPQQClient\History\DiscussHistory;
use Slince\PHPQQClient\History\GroupHistory;
use Slince\PHPQQClient\History\History;

class MessageSubscriber implements SubscriberInterface
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
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

    public function onReceiveGroupMessage(ReceivedMessageEvent $event)
    {
        $this->messageService->addHistory(new History(time(),
            $event->getMessage(),
            $event->getSender()
        ));
    }

    public function onReceiveDiscussMessage(ReceivedDiscussMessageEvent $event)
    {
        $this->messageService->addHistory(new DiscussHistory(time(),
            $event->getMessage(),
            $event->getSender()
        ));
    }

    public function onReceiveMessage(ReceivedGroupMessageEvent $event)
    {
        $this->messageService->addHistory(new GroupHistory(time(),
            $event->getMessage(),
            $event->getSender()
        ));
    }
}