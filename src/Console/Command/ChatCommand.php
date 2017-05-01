<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\MessagePanel;
use Slince\PHPQQClient\Constants;
use Slince\PHPQQClient\Event\Event;
use Slince\PHPQQClient\Event\ReceivedMessageEvent;
use Slince\PHPQQClient\Exception\InvalidArgumentException;
use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\Entity\Profile;
use Slince\SmartQQ\Message\Request\FriendMessage;
use Slince\SmartQQ\Message\Request\Message;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChatCommand extends Command
{
    /**
     * @var Friend
     */
    protected $friend;

    /**
     * @var Profile
     */
    protected $currentUser;

    protected static $prompt = '消息: ';

    public function configure()
    {
        $this->setName('chat');
        $this->addArgument('markname', InputArgument::REQUIRED, '好友昵称，备注');
        $this->setDescription('发起好友聊天');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $markName = $input->getArgument('markname');
        $friend = $this->getClient()->getFriends()->filter(function(Friend $friend) use ($markName){
            return $friend->getMarkName() == $markName
                || $friend->getNick() == $markName;
        })->first();
        if (is_null($friend)) {
            //如果找不到好友则可能是讨论组消息
            $discuss = $this->getClient()->getDiscusses()->filter(function(Discuss $discuss) use ($markName){
                return $discuss->getName() == $markName;
            })->first();
            if (!is_null($discuss)) {
                $discussChatCommand = $this->getClient()->find('discuss-chat');
                $discussChatCommand->run($input, $output);
                exit;
            }
            //如果找不到好友则可能是群聊天
            $group = $this->getClient()->getGroups()->filter(function(Group $group) use ($markName){
                return $group->getName() == $markName
                    || $group->getMarkName() == $markName;
            })->first();
            if (!is_null($group)) {
                $groupChatCommand = $this->getClient()->find('group-chat');
                $groupChatCommand->run($input, $output);
                exit;
            }
            throw new InvalidArgumentException("找不到聊天对象");
        }
        $this->friend = $friend;
        $this->currentUser = $this->getClient()->getCurrentUserProfile();
        $this->doChat();
    }

    protected function doChat()
    {
        //订阅消息接收，当前好友发送的消息展现到当前面板
        $this->getClient()->getDispatcher()->bind(Constants::EVENT_RECEIVE_MESSAGE, [$this, 'onReceived']);
        $this->loop();
    }

    protected function loop()
    {
        while (true) {
            $content = $this->getClient()->readLine(static::$prompt);
            if ($content) {
                $message = new FriendMessage($this->friend, $content);
                $result = $this->getClient()->sendMessage($message);
                if ($result) {
                    $messagePanel = $this->getClient()->createPanel(MessagePanel::class, [
                        $message,
                        $this->currentUser
                    ]);
                    $this->getOutput()->writeln((string)$messagePanel);
                }
            }
        }
    }

    /**
     * 处理收到消息
     * @param ReceivedMessageEvent $event
     */
    public function onReceived(ReceivedMessageEvent $event)
    {
        $sender = $event->getSender();
        //如果发件人不是当前聊天对象则忽略其消息
        if ($sender->getUin() !== $this->friend->getUin()) {
            return;
        }
        $messagePanel = $this->getClient()->createPanel(MessagePanel::class, [
            $event->getMessage(),
            $event->getSender()
        ]);
        $this->getOutput()->writeln($messagePanel);
    }
}