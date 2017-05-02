<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\Friend;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\EntityCollection;
use Symfony\Component\Console\Helper\Table;

class GroupsPanel extends Panel
{
    public function __construct($groups, $groupDetails = [])
    {
        parent::__construct([
            'groups' => $groups,
            'groupDetails' => $groupDetails,
        ]);
    }

    /**
     * @return EntityCollection
     */
    protected function getGroups()
    {
        return $this->getData()['groups'];
    }

    /**
     * @return EntityCollection
     */
    protected function getGroupDetails()
    {
        return $this->getData()['groupDetails'];
    }

    /**
     * @return array
     */
    protected function makeTable()
    {
        $headers = ['索引', '群名称', '群备注'];
        $groupDetails = $this->getGroupDetails();
        $rows = $this->getGroups()->map(function(Group $group, $index) use ($groupDetails){
            $groupDetail = isset($groupDetails[$group->getId()]) ? $groupDetails[$group->getId()] : false;
            return [
                ++ $index,
                $group->getName(),
                $group->getMarkName()
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
        $this->getStyle()->normalTable($headers, $rows);
    }
}