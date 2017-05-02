<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\EntityCollection;

class FriendsPanel extends Panel
{
    /**
     * @return EntityCollection
     */
    protected function getFriends()
    {
        return $this->getData();
    }

    /**
     * @return array
     */
    protected function makeTable2()
    {
        $friends = $this->getFriends()->groupBy(function(Friend $friend){
            return $friend->getCategory()->getName();
        })->toArray();
        $headers = array_keys($friends);
        $rows = [];
        for ($i = 0; $i < count(reset($friends)); $i++) {
            $row = [];
            foreach ($friends as $categoryName => $_friends) {
                $row[] = isset($_friends[$i]) ?
                    ($_friends[$i]->getMarkname() ?: $_friends[$i]->getNick()) : '';
            }
            $rows[] = $row;
        }
        return [$headers, $rows];
    }

    protected function makeTable()
    {
        $headers = ['好友', '昵称', '分类', 'VIP等级'];
        $rows = $this->getFriends()->map(function(Friend $friend){
            return [
                $friend->getMarkName() ?: $friend->getNick(),
                $friend->getNick(),
                $friend->getCategory()->getName(),
                $friend->getVipLevel()
            ];
        })->toArray();
        return [$headers, $rows];
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        list($headers, $rows) = $this->makeTable();
        $this->getStyle()->table($headers, $rows);
    }
}