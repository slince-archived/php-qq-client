<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console;

use Slince\PHPQQClient\Console\Panel\PanelInterface;
use Slince\PHPQQClient\Console\Panel\ProfilePanel;
use Slince\SmartQQ\Entity\Profile;

final class PanelFactory
{
    /**
     * @var PanelInterface[]
     */
    protected $panels = [];

    /**
     * 创建个人资料panel
     * @param Profile $profile
     * @return ProfilePanel
     */
    public static function createProfilePanel(Profile $profile)
    {
        return new ProfilePanel($profile);
    }

    protected static function createPanel($panelClass, array $arguments = [])
    {
        $reflection = new \ReflectionClass($panelClass);
    }
}