<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

use Slince\PHPQQClient\History\DiscussHistory;
use Slince\PHPQQClient\History\FriendHistory;
use Slince\PHPQQClient\History\GroupHistory;
use Slince\PHPQQClient\History\History;
use Slince\PHPQQClient\HistoryCollection;
use Slince\SmartQQ\Message\Response\FriendMessage;
use Slince\SmartQQ\Message\Response\GroupMessage;
use Slince\SmartQQ\Message\Response\DiscussMessage;

class MessageService extends Service
{
    /**
     * @var HistoryCollection
     */
    protected $historyCollection;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'listen-message';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
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

    public function run($callback = null)
    {
        $client = $this->getClient();
        //预先获取三组信息，防止获取消息时阻塞过长
        $friends = $client->getFriends();
        $groups = $client->getGroups();
        $discusses = $client->getDiscusses();
        while (true) {
            $messages = $client->polling();
            if (!$messages) {
                usleep(100);
            }
            $histories = [];
            foreach ($messages as $message) {
                if ($message instanceof FriendMessage) {
                    $friend = $friends->firstByAttribute('uin', $message->getFromUin());
                    $history = new FriendHistory(time(), $message, $friend);
                } elseif ($message instanceof GroupMessage) {
                    $group = $groups->firstByAttribute('id', $message->getFromUin());
                    $groupMember = $client->getGroupsDetail($group)->getMembers()
                        ->firstByAttribute('uin', $message->getSendUin());
                    $history = new GroupHistory(time(), $message, $group, $groupMember);
                } elseif ($message instanceof DiscussMessage) {
                    $discuss = $discusses->firstByAttribute('id', $message->getFromUin());
                    $discussMember = $client->getDiscussDetail($discuss)->getMembers()
                        ->firstByAttribute('uin', $message->getSendUin());
                    $history = new DiscussHistory(time(), $message, $discuss, $discussMember);
                } else {
                    //其它类型消息暂时不支持
                    continue;
                }
                $histories[] = $history;
                //添加入历史记录
                $this->addHistory($history);
            }
            //回调
            is_callable($callback) && call_user_func($callback, new HistoryCollection($histories));
        }
    }
}