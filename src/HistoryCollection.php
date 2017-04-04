<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

use Cake\Collection\Collection;
use Slince\PHPQQClient\History\DiscussHistory;
use Slince\PHPQQClient\History\GroupHistory;
use Slince\PHPQQClient\History\HistoryInterface;

class HistoryCollection extends Collection
{
    public function __construct(array $histories)
    {
        parent::__construct($histories);
    }

    /**
     * 添加一条历史
     * @param HistoryInterface $history
     */
    public function push(HistoryInterface $history)
    {
        $this->append([$history]);
    }

    /**
     * 搜索消息历史
     * @param $keyword
     * @return \Cake\Collection\Iterator\FilterIterator
     */
    public function search($keyword)
    {
        return $this->filter(function(HistoryInterface $history) use ($keyword){
            //先查找消息内容是否匹配关键词
            return strpos($history->getMessage(), $keyword) !== false ||
                //群组历史匹配消息发送人的昵称和群名片
                $history instanceof GroupHistory && (
                    strpos($history->getGroupMember()->getNick(), $keyword) !== false ||
                    strpos($history->getGroupMember()->getCard(), $keyword) !== false
                ) ||
                //讨论组消息匹配发送人昵称
                $history instanceof DiscussHistory && (
                    strpos($history->getDiscussMember()->getNick(), $keyword) !== false
                );
        });
    }
}