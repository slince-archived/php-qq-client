<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle;

class Style extends SymfonyStyle
{
    public function normalTable(array $headers, array $rows)
    {
        $table = new Table($this);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->render();
        $this->newLine();
    }
}