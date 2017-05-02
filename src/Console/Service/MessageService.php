<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

use Slince\PHPQQClient\Console\Command\MessageSubscriber;
use Slince\PHPQQClient\Console\Command\Service;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MessageService extends Service
{
    protected $messageCollection = [];

    public function addMessage()
    {

    }

    public function process()
    {
        $client = $this->getClient();
        $client->getDispatcher()->addSubscriber(new MessageSubscriber($this));
        while (true) {
            $messages = $client->polling();
            if (!$messages) {
                usleep(100);
            }

        }
    }
}