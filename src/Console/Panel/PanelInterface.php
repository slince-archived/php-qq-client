<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

interface PanelInterface
{
    /**
     * set data for panel
     * @param mixed $data
     * @return void
     */
    public function setData($data);

    /**
     * get data
     * @return mixed
     */
    public function getData();

    /**
     * render view
     * @return string
     */
    public function render();
}