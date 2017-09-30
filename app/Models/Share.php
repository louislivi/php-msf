<?php
namespace App\Models;

use PG\MSF\Models\Model;

/**
 * 微信分享参数Model类
 * Class Share
 * @package App\Models
 */
class Share extends Model
{
    /**
     * 设置微信分享参数
     * @param $data  string json数据
     * @return mixed
     */
    public function setWechatConfig($data)
    {
        $result = yield $this->getRedisPool('p1')->set('ConfigureByWechat', $data);
        return $result;
    }

    /**
     * 获取微信分享参数
     * @return mixed
     */
    public function getWechatConfig()
    {
        $data = yield $this->getRedisPool('p1')->get('ConfigureByWechat');

        return $data;
    }

    public function destroy()
    {
        parent::destroy();
    }
}