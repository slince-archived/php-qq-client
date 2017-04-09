<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

use Slince\Cache\CacheInterface;
use Slince\Event\Dispatcher;
use Slince\Event\SubscriberInterface;
use Slince\PHPQQClient\Console\Application;
use Slince\PHPQQClient\Event\ReceivedDiscussMessageEvent;
use Slince\PHPQQClient\Event\ReceivedGroupMessageEvent;
use Slince\PHPQQClient\Event\ReceivedMessageEvent;
use Slince\PHPQQClient\Exception\RuntimeException;
use Slince\SmartQQ\Client as SmartQQ;
use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\DiscussDetail;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\Exception\Code103ResponseException;
use Slince\SmartQQ\Exception\ResponseException;
use Slince\SmartQQ\Message\Response\FriendMessage;
use Slince\SmartQQ\Message\Response\GroupMessage;
use Slince\SmartQQ\Message\Response\DiscussMessage;

class Client extends Application
{
    /**
     * @var SmartQQ
     */
    protected $smartQQ;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var HistoryCollection[]
     */
    protected $histories = [];

    /**
     * @var array
     */
    protected $data = [];

    protected static $loginQrImage = __DIR__ . '/_login.png';

    public function __construct(Configuration $configuration, SmartQQ $smartQQ = null)
    {
        parent::__construct($configuration);
        $this->cache = $this->configuration->getCache();
        $this->smartQQ = $smartQQ ?: new SmartQQ();
    }

    /**
     * 获取登录凭证
     */
    public function login()
    {
        //优先使用缓存信息
        $credential = $this->cache->read(Constants::CACHE_CREDENTIAL, function(){
            $this->dispatcher->dispatch(Constants::EVENT_LOGIN);
            $credential = $this->smartQQ->login(static::$loginQrImage);
            $this->smartQQ->setCredential($credential);
        });
        $this->smartQQ->setCredential($credential);
    }

    public function getFriends()
    {
        if (isset($this->data['friends'])) {
            return $this->data['friends'];
        }
        $this->data['friends'] = $this->cache->read(Constants::CACHE_FRIENDS, function(){
            return $this->wrapRequest(function(){
                return $this->smartQQ->getFriends();
            });
        });
        return $this->data['friends'];
    }

    public function getGroups()
    {
        if (isset($this->data['groups'])) {
            return $this->data['groups'];
        }
        $this->data['groups'] = $this->cache->read(Constants::CACHE_GROUPS, function(){
            return $this->wrapRequest(function(){
                return $this->smartQQ->getGroups();
            });
        });
        return $this->data['groups'];
    }

    public function getDiscusses()
    {
        if (isset($this->data['discusses'])) {
            return $this->data['discusses'];
        }
        $this->data['discusses'] = $this->cache->read(Constants::CACHE_DISCUSSES, function(){
            return $this->wrapRequest(function(){
                return $this->smartQQ->getDiscusses();
            });
        });
        return $this->data['discusses'];
    }

    /**
     * 获取群详情
     * @param Group $group
     * @return DiscussDetail
     */
    public function getGroupsDetail(Group $group)
    {
        static $groupDetails = [];
        if (isset($groupDetails[$group->getId()])) {
            return $groupDetails[$group->getId()];
        }
        return $groupDetails[$group->getId()] = $this->wrapRequest(function() use ($group){
            return $this->smartQQ->getGroupDetail($group);
        });
    }

    /**
     * 获取讨论组详情
     * @param Discuss $discuss
     * @return DiscussDetail
     */
    public function getDiscussDetails(Discuss $discuss)
    {
        static $discussDetails = [];
        if (isset($discussDetails[$discuss->getId()])) {
            return $discussDetails[$discuss->getId()];
        }
        return $discussDetails[$discuss->getId()] = $this->wrapRequest(function() use ($discuss){
            return $this->smartQQ->getDiscussDetail($discuss);
        });
    }

    /**
     * 监听消息
     * @param SubscriberInterface|null $subscriber
     */
    public function listen(SubscriberInterface $subscriber = null)
    {
        if (!is_null($subscriber)) {
            $this->dispatcher->addSubscriber($subscriber);
        }
        //预先获取三组信息，防止获取消息时阻塞过长
        $friends = $this->getFriends();
        $groups = $this->getGroups();
        $discusses = $this->getDiscusses();
        //轮询消息
        $messages = $this->wrapRequest(function(){
            $this->smartQQ->pollMessages();
        });
        foreach ($messages as $message) {
            if ($message instanceof FriendMessage) {
                $friend = $friends->firstByAttribute('uin', $message->getFromUin());
                $event = new ReceivedMessageEvent($message, $friend, $this);
            } elseif ($message instanceof GroupMessage) {
                $group = $groups->firstByAttribute('id', $message->getFromUin());
                $groupMember = $this->getGroupsDetail($group)->getMembers()
                    ->firstByAttribute('uin', $message->getSendUin());
                $event = new ReceivedGroupMessageEvent($message, $group, $groupMember, $this);
            } elseif ($message instanceof DiscussMessage) {
                $discuss = $discusses->firstByAttribute('id', $message->getFromUin());
                $discussMember = $this->getDiscussDetails($discuss)->getMembers()
                    ->firstByAttribute('uin', $message->getSendUin());
                $event = new ReceivedDiscussMessageEvent($message, $discuss, $discussMember, $this);
            } else {
                //其它类型消息暂时不支持
                continue;
            }
            $this->dispatcher->dispatch($event->getName(), $event);
        }
    }

    protected function setup()
    {
    }

    /**
     * @param \Closure $callback
     * @return mixed|false
     */
    protected function wrapRequest(\Closure $callback)
    {
        $attempts = 0;
        while (true) {
            if ($attempts >= 3) {
                break;
            }
            try {
                return call_user_func($callback);
            } catch (Code103ResponseException $exception) {
                throw new RuntimeException("登录异常，请先登录http://w.qq.com确认可以收发消息然后退出重试");
            } catch (ResponseException $exception) {
                $attempts ++;
            }
        }
        return false;
    }
}