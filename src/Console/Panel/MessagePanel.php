<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\User;
use Slince\SmartQQ\Message\Message;

class MessagePanel extends Panel
{
    public function __construct(Message $message, User $sender)
    {
        parent::__construct([
            'message' => $message,
            'sender' => $sender
        ]);
    }

    public function render()
    {
        $message = $this->getMessage();
        $sender = $this->getSender();
        //优先使用备注名，否则使用昵称
        $senderName = (method_exists($sender, 'getMarkName') ?
            $sender->getMarkName() : false) ?:
            $sender->getNick();
        return "{$senderName}: {$message->getContent()->getContent()}";
    }

    /**
     * @return Message
     */
    protected function getMessage()
    {
        return $this->getData()['message'];
    }

    /**
     * @return User
     */
    protected function getSender()
    {
        return $this->getData()['sender'];
    }
}