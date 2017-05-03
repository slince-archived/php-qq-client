<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Service;

interface ServiceInterface
{
    public function run($callback = null);

    public function getName();
}