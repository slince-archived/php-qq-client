<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\DiscussMember;

class DiscussMembersPanel extends Panel
{
    public function __construct($discuss, $discussDetail, $members)
    {
        parent::__construct([$discuss, $discussDetail, $members]);
    }

    protected function makeTable()
    {
        $headers = ['编号', '昵称'];
        $rows = $this->getData()[2]->map(function(DiscussMember $member, $index){
            return [
                $index + 1,
                $member->getNick(),
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