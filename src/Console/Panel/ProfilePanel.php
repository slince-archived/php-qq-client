<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\Profile;

class ProfilePanel extends Panel
{
    /**
     * 生成表格信息
     * @param Profile $profile
     * @return array
     */
    protected function makeTable(Profile $profile)
    {
        $birthday = $profile->getBirthday();
        $age = ceil((time() - strtotime("{$birthday->getYear()}-{$birthday->getMonth()}-{$birthday->getDay()}"))
            / (86400 * 365));
        $tableRows = [
            [$profile->getNick(), $age . '岁' . ' '
                .  $profile->getCountry() . $profile->getProvince() . $profile->getCity()
            ],
            ['QQ', $profile->getAccount()],
            ['手机号', $profile->getMobile()],
            ['邮箱', $profile->getEmail()],
            ['签名', $profile->getLnick()],
            ['VIP等级', $profile->getVipInfo()]
        ];
        return [[], $tableRows];
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        list($headers, $rows) = $this->makeTable($this->data);
        $this->getStyle()->table($headers, $rows);
    }
}