<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console\Panel;

use Slince\SmartQQ\Entity\Profile;
use Symfony\Component\Console\Helper\TableCell;

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
            ['昵称', $profile->getNick()],
            ['生日', "{$birthday->getYear()}/{$birthday->getMonth()}/{$birthday->getDay()} {$age}岁"],
            ['性别', $profile->getGender() == 'male' ? '男' : '女'],
            $profile->getAccount() ? ['QQ', $profile->getAccount()] : false,
            ['国家', $profile->getCountry()],
            ['省份', $profile->getProvince()],
            ['城市', $profile->getCity()],
            ['邮箱', $profile->getEmail()],
            ['VIP等级', $profile->getVipInfo()],
            ['个性签名', $profile->getLnick()],
        ];
        return [[], array_filter($tableRows)];
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