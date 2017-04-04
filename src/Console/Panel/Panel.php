<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

abstract class Panel implements PanelInterface
{
    /**
     * data source
     * @var mixed
     */
    protected $data;

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}