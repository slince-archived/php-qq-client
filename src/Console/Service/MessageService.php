<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

use Slince\PHPQQClient\Console\Command\MessageSubscriber;
use Slince\PHPQQClient\Console\Command\Service;
use Slince\PHPQQClient\History\History;
use Slince\PHPQQClient\HistoryCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MessageService extends Service
{
    /**
     * @var HistoryCollection
     */
    protected $historyCollection;

    public function __construct()
    {
        parent::__construct('message-service');
        $this->historyCollection = new HistoryCollection();
    }

    /**
     * Adds a history to collection
     * @param History $history
     */
    public function addHistory(History $history)
    {
        $this->historyCollection->push($history);
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