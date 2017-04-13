<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Entity;

use Slince\SmartQQ\Entity\Friend as BaseFriend;
use Slince\SmartQQ\Entity\Profile;

class Friend extends BaseFriend
{
    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }
}