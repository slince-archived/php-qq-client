<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Command;

use Slince\PHPQQClient\Console\Panel\DiscussesPanel;
use Slince\SmartQQ\Entity\Discuss;
use Slince\SmartQQ\Entity\Group;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDiscussesCommand extends Command
{
    public function configure()
    {
        $this->setName('list-discusses');
        $this->addOption('names', null, InputOption::VALUE_OPTIONAL, '按名称筛选');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $discusses = $this->getClient()->getDiscusses();
        //按照昵称或者备注筛选
        if ($names = $input->getOption('names')) {
            $names = array_filter(explode(',', $names));
            $discusses = $discusses->filter(function(Discuss $discuss) use ($names){
                return in_array($discuss->getName(), $names);
            });
        }
        $panel = new DiscussesPanel($discusses);
        $panel->render();
    }
}