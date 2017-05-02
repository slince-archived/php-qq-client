<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\Group;
use Slince\SmartQQ\EntityCollection;

class DiscussesPanel extends Panel
{
    public function __construct($discusses)
    {
        parent::__construct([
            'discusses' => $discusses,
        ]);
    }

    /**
     * @return EntityCollection
     */
    protected function getDiscusses()
    {
        return $this->getData()['discusses'];
    }

    /**
     * @return array
     */
    protected function makeTable()
    {
        $headers = ['索引', '名称'];
        $rows = $this->getDiscusses()->map(function(Discuss $discuss, $index){
            return [
                ++ $index,
                $discuss->getName(),
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