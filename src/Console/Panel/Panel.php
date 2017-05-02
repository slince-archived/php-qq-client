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
    protected static $style;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->render();
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
    public static function getStyle()
    {
        return static::$style;
    }

    /**
     * @param Style $style
     */
    public static function setStyle($style)
    {
        static::$style  = $style;
    }
}