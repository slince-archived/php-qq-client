<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\Category;

class CategoriesPanel extends Panel
{
    /**
     * @return array
     */
    protected function makeTable()
    {
        $headers = ['索引', '名称'];
        $index = 0;
        $rows = $this->getData()->map(function(Category $category) use (&$index){
            $index ++;
            return [
                $index,
                $category->getName(),
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