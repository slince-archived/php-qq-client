<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\PHPQQClient\Console\Style;

abstract class Panel implements PanelInterface
{
    /**
     * data source
     * @var mixed
     */
    protected $data;

    /**
     * @var Style
     */
    protected $style;

    public function __construct($data)
    {
        $this->data = $data;
    }

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

    /**
     * {@inheritdoc}
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param Style $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }
}