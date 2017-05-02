<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\CategoriesPanel;
use Slince\SmartQQ\Entity\Category;
use Slince\SmartQQ\Entity\Friend;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCategoriesCommand extends Command
{
    public function configure()
    {
        $this->setName('show-categories')
            ->setDescription('查看所有好友分类');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $existsCategories = [];
        $categories = $this->getClient()->getFriends()->map(function(Friend $friend){
            return $friend->getCategory();
        })->filter(function(Category $category) use (&$existsCategories){
            if (isset($existsCategories[$category->getIndex()])) {
                return false;
            }
            $existsCategories[$category->getIndex()] = $category;
            return true;
        })->sortBy(function(Category $category){
            return $category->getSort();
        }, SORT_ASC);
        $panel = $this->getClient()->createPanel(CategoriesPanel::class, [$categories]);
        $panel->render();
    }
}