<?php
/**
 * Demo模型
 */
namespace App\Models;

use PG\MSF\Models\Model;

/**
 * 广告Model类
 * Class Advertisement
 * @package App\Models
 */
class Advertisement extends Model
{
    /**
     * 获取广告列表
     * @return mixed
     */
    public function getLists()
    {
        $column = 'id,title';
        $bizLists  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('advertisement')
            ->where('if_del','0')
            ->orderBy('id','desc')
            ->go();
        return $bizLists;
    }

    /**
     * 获取首页广告
     * @return bool|string
     */
    public function getHomeSetting()
    {
        $redisPool = $this->getRedisPool('p1');
        return $redisPool ->get('index_adver_set');
    }

    /**
     * 设置首页广告
     * @param $value
     * @return bool
     */
    public function setHomeSetting($value)
    {
        $redisPool = $this->getRedisPool('p1');
        return $redisPool ->set('index_adver_set',$value);
    }

    /**
     * 获取广告详细列表
     * @param $limit integer limit
     * @return mixed
     */
    public function getDetailsLists($limit)
    {
        $column = '*';
        $bizLists  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('advertisement','a')
            ->leftJoin('adver_total','a.id = t.ad_id','t')
            ->where('if_del','0')
            ->limit($limit)
            ->orderBy('a.id','desc')
            ->go();
        return $bizLists;
    }

    /**
     * 获取广告总数
     */
    public function getTotal()
    {
        $result  = yield $this->getMysqlPool('master')
            ->select('count(*) as total')
            ->from('advertisement')
            ->where('if_del','0')
            ->go();
        return $result['result'][0]['total'];
    }

    /**
     * 添加广告
     * @param $data array 数据
     */
    public function addAdvertisement($data)
    {

        $result  = yield $this->getMysqlPool('master')
            ->insert('advertisement')
            ->set($data)
            ->go();
        yield $this->getMysqlPool('master')
            ->insert('adver_total')
            ->set([
                'ad_id'=>$result['insert_id'],
                'click_num' => '0'
            ])
            ->go();
        return $result['result'];
    }

    /**
     * 修改广告
     * @param $id integer 广告id
     * @param $data array 数据
     */
    public function editAdvertisement($id,$data)
    {
        $result  = yield $this->getMysqlPool('master')
            ->update('advertisement')
            ->where('id',$id)
            ->set($data)
            ->go();
        return $result['result'];
    }

    /**
     * 获取广告详情
     * @param $id integer 广告id
     */
    public function getAdvertisementDetails($id)
    {
        $column = '*';
        $bizDetails  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('advertisement')
            ->where('id',$id)
            ->go();
        return $bizDetails;
    }

    /**
     * 删除广告
     * @param $id integer 广告id
     */
    public function deleteAdvertisement($id)
    {
        $result  = yield $this->getMysqlPool('master')
            ->update('advertisement')
            ->where('id',$id)
            ->set(['if_del'=>'1'])
            ->go();
        return $result['result'];
    }
}