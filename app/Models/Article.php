<?php
namespace App\Models;

use PG\MSF\Models\Model;

/**
 * 文章Model类
 * Class Article
 * @package App\Models
 */
class Article extends Model
{

    /**
     * 获取文章列表
     * @param $limit integer 分页
     * @return mixed
     */
    public function getLists($limit,$orderBy)
    {
        $column = 'a.id,a.title,a.create_time,i.share_num,i.zan,i.views';
        $bizLists  = yield $this->getMysqlPool('master')
                                ->select($column)
                                ->from('article','a')
                                ->where('a.if_del','0')
                                ->leftJoin('article_info','a.id = i.article_id','i')
                                ->limit($limit)
                                ->orderBy($orderBy,'desc')
                                ->go();
        return $bizLists;
    }

    /**
     * 获取文章详情
     * @param $id integer 文章id
     */
    public function getDetails($id)
    {
        $column = 'a.*,d.contents,r.recommend_time as recommend';
        $bizDetails  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('article','a')
            ->where('a.id',$id)
            ->leftJoin('article_info','a.id = i.article_id','i')
            ->leftJoin('article_data','a.id = d.article_id','d')
            ->leftJoin('article_recommend','a.id = r.article_id','r')
            ->groupBy('a.id','desc')
            ->go();
        return $bizDetails;
    }

    /**
     * 获取文章总数
     */
    public function getTotal()
    {
        $result  = yield $this->getMysqlPool('master')
            ->select('count(*) as total')
            ->from('article')
            ->where('if_del','0')
            ->go();
        return $result['result'][0]['total'];
    }

    /**
     * 删除文章
     *
     * @param $id integer 文章id
     */
    public function delArticle($id)
    {
        $result  = yield $this->getMysqlPool('master')
            ->update('article')
            ->set(['if_del' => '1'])
            ->where('id',$id)
            ->go();
        return $result['result'];
    }

    /**
     * 添加文章
     * @param $data array 添加数据
     * @return mixed
     */
    public function addArticle($data)
    {
        $recommend = $data['recommend']? true :false;
        $contents =  $data['contents'];
        unset($data['recommend']);
        unset($data['contents']);
        if (isset($data['ding'])){
            $data['ding'] = 1;
        }else{
            $data['ding'] = 0;
        }
        $result  = yield $this->getMysqlPool('master')
            ->insert('article')
            ->set($data)
            ->go();
        $id = $result['insert_id'];
        if ($recommend){
            yield $this->getMysqlPool('master')
                ->insert('article_recommend')
                ->set(['article_id' => $id,'recommend_time' => date('Y-m-d H:i:s')])
                ->go();
        }
        yield $this->getMysqlPool('master')
            ->insert('article_info')
            ->set(['article_id' => $id,'views' => '0','zan' => '0','share_num' => '0'])
            ->go();
        yield $this->getMysqlPool('master')
            ->insert('article_data')
            ->set(['article_id' => $id,'contents' => $contents,'create_time' => date('Y-m-d H:i:s')])
            ->go();
        return $result['result'];

    }

    /**
     * 修改文章
     * @param $id integer 文章id
     * @param $data array 修改数据
     * @return mixed
     */
    public function saveArticle($id,$data)
    {
        if (!empty($data)){
            $data['create_time'] = date('Y-m-d H:i:s');
        }

        $has_recommend  = yield $this->getMysqlPool('master')
            ->select('article_id')
            ->from('article_recommend','a')
            ->where('article_id',$id)
            ->go();
        if (isset($data['recommend'])){
            if (!isset($has_recommend['result'][0]['article_id'])){
                yield $this->getMysqlPool('master')
                    ->insert('article_recommend')
                    ->set(['article_id' => $id,'recommend_time' => date('Y-m-d H:i:s')])
                    ->go();
            }
        }else{
            if (isset($has_recommend['result'][0]['article_id'])){
                yield $this->getMysqlPool('master')
                    ->delete('FROM article_recommend')
                    ->where('article_id',$id,'=')
                    ->go();
            }
        }
        if (isset($data['ding'])){
            $data['ding'] = 1;
        }else{
            $data['ding'] = 0;
        }
        $has_article_data = yield $this->getMysqlPool('master')
            ->select('article_id')
            ->from('article_data')
            ->where('article_id',$id)
            ->go();
        if (isset($has_article_data['result'][0]['article_id'])){
            yield $this->getMysqlPool('master')
                ->update('article_data')
                ->set(['contents' => $data['contents'],'create_time' => date('Y-m-d H:i:s')])
                ->where('article_id',$id)
                ->go();
        }else{
            yield $this->getMysqlPool('master')
                ->insert('article_data')
                ->set(['article_id' => $id,'contents' => $data['contents'],'create_time' => date('Y-m-d H:i:s')])
                ->go();
        }
        unset($data['recommend']);
        unset($data['contents']);
        $result  = yield $this->getMysqlPool('master')
            ->update('article')
            ->set($data)
            ->where('id',$id)
            ->go();
        return $result['result'];

    }

    /**
     * 获取文章作者
     * @param $id integer 文章id
     */
    public function getArticleAccount($id)
    {
        $column = 'w.aname,w.url,w.id';
        $bizList  = yield $this->getMysqlPool('master')
                ->select($column)
                ->from('article_with_account','w')
                ->leftJoin('article','a.id = w.article_id','a')
                ->where('w.if_del','0')
                ->andWhere('w.article_id',$id)
                ->orderBy('w.id','desc')
                ->go();
        return $bizList;
    }

    /**
     * 删除文章作者
     *
     * @param $id integer 作者id
     */
    public function delArticleAccount($id)
    {
        $result  = yield $this->getMysqlPool('master')
            ->update('article_with_account')
            ->set(['if_del' => '1'])
            ->where('id',$id)
            ->go();
        return $result['result'];
    }

    /**
     * 添加文章作者
     * @param $data
     * @return mixed
     */
    public function addArticleAccount($data)
    {
        $result  = yield $this->getMysqlPool('master')
            ->insert('article_with_account')
            ->set($data)
            ->go();
        return $result['result'];
    }

    public function destroy()
    {
        parent::destroy();
    }
}