<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

class Loop
{
    public function run(callable $callable)
    {
        while (true) {
            call_user_func($callable);
        }
    }
}