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
    public function getLists($limit,$orderBy )
    {
        $column = 'a.id,a.title,a.create_time,i.share_num,i.zan,i.views,i.views_offset';
        $bizLists  = yield $this->getMysqlPool('master')
                                ->select($column)
                                ->from('article','a')
                                ->innerJoin('article_info','a.id = i.article_id','i')
                                ->where('a.if_del',0)
                                ->limit($limit)
                                ->orderBy($orderBy,'desc')
                                ->go();
        return $bizLists;
    }

    /**
     * 获取文章指定id，列表信息,并储存到redis
     * @param $id
     * @return mixed
     */
    public function collectionListDetails($id)
    {
        $column = 'a.id,a.title,a.create_time,i.share_num,i.zan,i.views,i.views_offset';
        $bizLists  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('article','a')
            ->innerJoin('article_info','a.id = i.article_id','i')
            ->where('a.if_del',0)
            ->andWhere('a.id',$id)
            ->go();
        $redisPool = $this->getRedisPool('p1');
        $result = yield $redisPool->hMset('collection:1', [$id => json_encode($bizLists)]);
        return $result == "OK"?true:false;
    }

    /**
     * 获取文章详情
     * @param $id integer 文章id
     */
    public function getDetails($id)
    {
        //$column = 'a.*,d.contents,r.recommend_time as recommend,tops.create_time as ding';
        $column = 'a.*,i.views_offset,d.contents,tops.create_time as ding';
        $bizDetails  = yield $this->getMysqlPool('master')
            ->select($column)
            ->from('article','a')
            ->where('a.id',$id)
            ->innerJoin('article_info','a.id = i.article_id','i')
            ->innerJoin('article_data','a.id = d.article_id','d')
            //->leftJoin('article_recommend','a.id = r.article_id','r')
	    ->leftJoin('article_top','a.id = tops.article_id','tops')
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
        if (! empty($data) ) {
            $data['create_time'] = date('Y-m-d H:i:s');
        }
        //$recommend = $data['recommend']? true :false;
        $contents =  $data['contents'];
        $views_offset =  $data['views_offset'];
        //unset($data['recommend']);
        unset($data['contents']);
        unset($data['views_offset']);
        $isding = false;
        if( isset($data['ding']) ) {
            $isding = true;
            unset($data['ding']);
        }
        //生成栏目序列值
        $mysqlPool = $this->getMysqlPool('master');
        $categoryCustomSeqId = yield $mysqlPool
            ->select('ccs_id')
            ->from('article')
            ->where('category_id',$data['category_id'])
            ->orderBy('ccs_id','desc')
            ->limit(1)
            ->go();
        $categoryCustomSeqId = $categoryCustomSeqId['result'][0]['ccs_id'];
        $data['ccs_id'] = (int)$categoryCustomSeqId + 1;
        $result  = yield $mysqlPool
            ->insert('article')
            ->set($data)
            ->go();
        $id = $result['insert_id'];
        if ( false === $isding  ) {
           yield $mysqlPool->go(null,"delete from `article_top` where article_id = ".intval($id));
        } else {
          $isExist =  yield $mysqlPool->select('article_id')->from('article_top')->where('article_id',$id)->go();
             if(! isset( $isExist['result'][0] ) ) {
                  $nowtime = date('Y-m-d H:i:s');
                  yield $mysqlPool->go(null,"insert into article_top(article_id,category_id,create_time) values
         ({$id},{$data['category_id']},'{$nowtime}')");
             }
        }

        /*$redisPool = $this->getRedisPool('p1');
        yield $redisPool->set("category{$data['category_id']}_max_id",$id);
        yield $redisPool->save();*/

        /*if ($recommend){
            yield $mysqlPool
                ->insert('article_recommend')
                ->set(['article_id' => $id,'recommend_time' => date('Y-m-d H:i:s')])
                ->go();
        }*/
        yield $mysqlPool
            ->insert('article_info')
            ->set([
                'article_id' => $id,
                'views' => '0',
                'zan' => '0',
                'share_num' => '0',
                'views_offset' => $views_offset
            ])
            ->go();
        yield $mysqlPool
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
        if (! empty($data) ) {
            $data['create_time'] = date('Y-m-d H:i:s');
        }



        $mysqlPool = $this->getMysqlPool('master');

        //设置文章虚拟阅读数
        if (isset($data['views_offset'])){
            yield $mysqlPool
                ->update('article_info')
                ->set(['views_offset' => $data['views_offset']])
                ->where('article_id',$id)
                ->go();
            unset($data['views_offset']);
        }

        $dataExist = yield $mysqlPool->select('*')->from('article')->where('id',$id)->limit(1)->go();
        if(! isset( $dataExist['result'][0] ) ) return false;

        //处理置顶
        if (! isset($data['ding']) ) {
	        yield $mysqlPool->go(null,"delete from `article_top` where article_id = ".intval($id));
        } else {
            $isExist =  yield $mysqlPool->select('article_id')->from('article_top')->where('article_id',$id)->go();
            if(! isset( $isExist['result'][0] ) ) {
                $nowtime = date('Y-m-d H:i:s');
                yield $mysqlPool->go(null,"insert into article_top(article_id,category_id,create_time) values ({$id},{$data['category_id']},'{$nowtime}')");
            }
            unset($data['ding']);
        }

        //添加文章内容
        $has_article_data = yield $mysqlPool->select('article_id')
            ->from('article_data')
            ->where('article_id',$id)
            ->go();
        if (isset($has_article_data['result'][0]['article_id'])){
            yield $mysqlPool->update('article_data')
                ->set(['contents' => $data['contents'],'create_time' => date('Y-m-d H:i:s')])
                ->where('article_id',$id)
                ->go();
        } else {
            yield $mysqlPool->insert('article_data')
                ->set(['article_id' => $id,'contents' => $data['contents'],'create_time' => date('Y-m-d H:i:s')])
                ->go();
        }
        //unset($data['recommend']);
        unset($data['contents']);


        //改变文章所属栏目
        if( $dataExist['result'][0]['category_id'] <> $data['category_id'] ) {
            //生成栏目序列值
            $categoryCustomSeqId = yield $mysqlPool
                ->select('ccs_id')
                ->from('article')
                ->where('category_id',$data['category_id'])
                ->orderBy('ccs_id','desc')
                ->limit(1)
                ->go();
            $categoryCustomSeqId = $categoryCustomSeqId['result'][0]['ccs_id'];
            $data['ccs_id'] = (int)$categoryCustomSeqId + 1;
        }

        //添加文章信息
        $result  = yield $mysqlPool->update('article')
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
