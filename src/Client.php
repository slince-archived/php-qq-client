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
use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\Entity\Profile;
use Slince\SmartQQ\EntityCollection;
use Slince\SmartQQ\Exception\Code103ResponseException;
use Slince\SmartQQ\Exception\ResponseException;
use Slince\SmartQQ\Message\Request\Message;
use Slince\SmartQQ\Message\Response\Message as ResponseMessage;

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

    protected static $loginQrImage;

    public function __construct(Configuration $configuration, SmartQQ $smartQQ = null)
    {
        parent::__construct($configuration);
        $this->cache = $this->configuration->getCache();
        $this->smartQQ = $smartQQ ?: new SmartQQ();
        static::$loginQrImage = $this->configuration->getConfig('loginImage');
    }

    /**
     * 获取登录凭证
     */
    public function login()
    {
        //优先使用缓存信息
        $credential = $this->cache->read(Constants::CACHE_CREDENTIAL, function(){
            $this->dispatcher->dispatch(Constants::EVENT_LOGIN);
            return $this->smartQQ->login(static::$loginQrImage);
        });
        $this->smartQQ->setCredential($credential);
    }

    /**
     * 获取所有好友
     * @return EntityCollection
     */
    public function getFriends()
    {
        if (isset($this->data['friends'])) {
            return $this->data['friends'];
        }
        $this->data['friends'] = $this->wrapRequest(function(){
            return $this->smartQQ->getFriends();
        });
        return $this->data['friends'];
    }

    /**
     * 获取所有群
     * @return EntityCollection
     */
    public function getGroups()
    {
        if (isset($this->data['groups'])) {
            return $this->data['groups'];
        }
        $this->data['groups'] = $this->wrapRequest(function(){
            return $this->smartQQ->getGroups();
        });
        return $this->data['groups'];
    }

    public function getDiscusses()
    {
        if (isset($this->data['discusses'])) {
            return $this->data['discusses'];
        }
        $this->data['discusses'] = $this->wrapRequest(function(){
            return $this->smartQQ->getDiscusses();
        });
        return $this->data['discusses'];
    }

    /**
     * 获取当前用户的资料
     * @return Profile
     */
    public function getCurrentUserProfile()
    {
        if (isset($this->data['currentUser'])) {
            return $this->data['currentUser'];
        }
        return $this->data['currentUser'] = $this->wrapRequest(function(){
            return $this->smartQQ->getCurrentUserInfo();
        });
    }

    /**
     * 获取好友信息
     * @param Friend $friend
     * @return Friend
     */
    public function getFriendDetail(Friend $friend)
    {
        $key = 'friend' . $friend->getUin();
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        //获取基本消息
        $detail = $this->wrapRequest(function() use ($friend){
            return $this->smartQQ->getFriendDetail($friend);
        });
        //获取好友qq号，腾讯已经封禁了此接口
        /* $detail->setAccount($this->wrapRequest(function() use($friend){
            return $this->smartQQ->getFriendQQ($friend);
        }));*/
        //获取好友lnick
        $detail->setLnick($this->wrapRequest(function() use($friend){
            return $this->smartQQ->getFriendLnick($friend);
        }));
        return $this->data[$key] = $detail;
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
    public function getDiscussDetail(Discuss $discuss)
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
     * Sends a message
     * @param Message $message
     * @return false|mixed
     */
    public function sendMessage(Message $message)
    {
        return $this->wrapRequest(function() use($message){
            return $this->smartQQ->sendMessage($message);
        });
    }

    /**
     * 监听消息
     * @return ResponseMessage[]
     */
    public function polling()
    {
        //轮询消息
        return $this->wrapRequest(function(){
            return $this->smartQQ->pollMessages();
        });
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