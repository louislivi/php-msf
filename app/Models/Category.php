<?php
namespace App\Models;

use PG\MSF\Models\Model;

/**
 * 栏目Model类
 * Class Category
 * @package App\Models
 */
class Category extends Model
{
    /**
     * 获取栏目列表
     * @return mixed
     */
    public function getLists()
    {
        $column = 'c1.*,c2.title as p_title';
        $bizLists  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('category','c1')
            ->leftJoin('category','c1.pid = c2.id','c2')
            ->where('c1.pid',0)
            ->orderBy('front_sort','asc')
            ->go();
        return $bizLists;
    }

    /**
     * 获取父级栏目列表
     * @return mixed
     */
    public function getParentLists()
    {
        $column = 'id,title';
        $bizLists  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('category')
            ->where('pid','0')
            ->orderBy('front_sort','asc')
            ->go();
        return $bizLists;
    }

    /**
     * 添加栏目
     * @param $data array 数据
     */
    public function addCategory($data)
    {
        $result  = yield $this->getMysqlPool('master')
            ->insert('category')
            ->set($data)
            ->go();
        return $result['result'];
    }

    /**
     * 修改栏目
     * @param $id integer 栏目id
     * @param $data array 数据
     */
    public function editCategory($id,$data)
    {
        $result  = yield $this->getMysqlPool('master')
            ->update('category')
            ->where('id',$id)
            ->set($data)
            ->go();
        return $result['result'];
    }

    /**
     * 获取栏目详情
     * @param $id integer 栏目id
     */
    public function getCategoryDetails($id)
    {
        $column = '*';
        $bizDetails  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('category')
            ->where('id',$id)
            ->go();
        return $bizDetails;
    }

    /**
     * 删除栏目
     * @param $id integer 栏目id
     */
    public function deleteCategory($id)
    {
        $article_total = yield $this->getMysqlPool('master')
            ->select('count(*) as article_total')
            ->from('article')
            ->where('category_id',$id)
            ->where('if_del',0)
            ->go();
        if ( $article_total['result'][0]['article_total'] ) {
            return false;
        }else{
            $result  = yield $this->getMysqlPool('master')
                ->delete('FROM category')
                ->where('id',$id)
                ->go();
            return $result['result'];
        }
    }


}