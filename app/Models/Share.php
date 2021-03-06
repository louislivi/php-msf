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
        $redisPool = $this->getRedisPool('p1');
        $result = yield $redisPool->set('ConfigureByWechat', $data);
        yield $redisPool->del('wechat_access_token', 'wechat_jsapi_ticket');
        yield $redisPool->save();
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