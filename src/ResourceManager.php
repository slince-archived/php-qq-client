<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient;

use Slince\SmartQQ\Client as SmartQQ;

class ResourceManager
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var SmartQQ
     */
    protected $smartQQ;

    public function __construct(SmartQQ $smartQQ)
    {
        $this->smartQQ = $smartQQ;
    }
}