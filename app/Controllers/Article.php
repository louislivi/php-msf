<?php
namespace App\Controllers;
use App\Models\Advertisement as AdvertisementModel;
use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\Collection as CollectionModel;
use App\Models\Handlers\Page;
use \App\Tasks\Upload;
use \PG\MSF\Client\Http\Client;
//use \App\Models\Handlers\Upload;
use App\Models\Share as ShareModel;

/**
 * 文章管理类
 * Class Article
 * @package App\Controllers
 */
class Article extends AdminAuth
{
    /**
     * 文章列表页
     */
    public function actionIndex()
    {
        $sort = $this->getContext()->getInput()->get('sort');
        //查询收藏列表
        $collectionModel  = $this->getObject(CollectionModel::class);
        $collection = yield $collectionModel ->getListsKeys();
        if ($key_word = $this->getContext()->getInput()->get('search')){
            //搜索
//            switch ($sort){
//                case 'zan':
//                    $orderBy = 'zan';
//                    break;
//                case 'yue':
//                    $orderBy = 'views';
//                    break;
//                case 'fen':
//                    $orderBy = 'share_num';
//                    break;
//                default:
//                    $orderBy = 'create_time';
//                    break;
//            }
            $searchUrl = "http://127.0.0.1:8000/search/index.php";
            $data = [
                'q'    => urldecode($key_word),
                'm'    => 'yes',
                'f'    => '_all',
                's'    => 'relevance',
                'o'    => '',
                'n'    => 25,
                'json' => 'yes',
                'p'    => $this->getContext()->getInput()->get('page')?:1
            ];
            //请求全文搜索api
            $client  = $this->getObject(Client::class);
//            //发送请求
            $result  = yield $client->goSingleGet($searchUrl,$data);
            $search_body = json_decode($result['body'],true);
            //搜索结果分页
            $total = $search_body['count'];
            if($total > 0){
                if(isset($search_body['data'])) {
                    $page  = $this ->getObject(Page::class,[
                        $total,
                        isset($data['n'])?$data['n']:'',
                        'search='.$key_word]);
                    $page ->set('view_last',false);
                    $pageList = $page ->fpage();
                    $page ->set('view_last',true);
                    $view_data['pageList'] = $pageList;
                    $view_data['search']   = $key_word;
                    $view_data['result']   = $search_body['data'];
                    $view_data['collection']   = $collection;
                    $this ->outputView($view_data);
                    return;
                }else{
                    $this ->error('页码值错误!');
                }
            }else{
                $this ->error('未找到搜索结果!');
            }
            return;
        }
        switch ($sort){
            case 'zan':
                $orderBy = 'i.zan';
                break;
            case 'yue':
                $orderBy = 'i.views';
                break;
            case 'fen':
                $orderBy = 'i.share_num';
                break;
            default:
                $orderBy = 'a.id';
                break;
        }
        $articleModel = $this->getObject(ArticleModel::class);
        $total = yield $articleModel->getTotal();
        $page = $this ->getObject(Page::class,[$total]);
        $limit = $page ->limit;
        $data         = yield $articleModel->getLists($limit,$orderBy);
        $pageList = $page ->fpage();
        $data['pageList']   = $pageList;
        $data['collection'] = $collection;
        $this->outputView($data);
    }

    /**
     * 文章详情
     * @param $id integer
     */
    public function actionArticleDetails($id)
    {

        $this->outputView([$id]);
    }

    /**
     * 添加文章
     */
    public function actionArticleAdd()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        if (empty($post) || $post == false){
            $advertisementModel = $this ->getObject(AdvertisementModel::class);
            $advertisement         = yield $advertisementModel->getLists();
            $categoryModel = $this->getObject(CategoryModel::class);
            $category         = yield $categoryModel->getLists();
            $data['advertisement'] = $advertisement;
            $data['category']       = $category;
            $this->outputView($data);
        }else{
            //$this->output($post);return;
            $articleModel = $this->getObject(ArticleModel::class);
            $upload = $this->getObject(Upload::class);
            try{
                $upload_condition =
                    !empty(
                        $this->getContext()->getInput()->getFile('cover_src')['name']);
            }catch (\Exception $exception){
                $upload_condition =
                    isset($this->getContext()->getInput()->getFile('cover_src')['name']);
            }
            if ($upload_condition){
                $result = yield $upload->article_upload("cover_src");
                if($result[0]){
                    $filename = $result[1];
                    $true_filename = '/article/'.$filename;
                    $post['cover_src'] = $true_filename;
                } else {
                    $errMsg = yield $upload->getErrorMsg();
                    $this->error( $errMsg );
                }
            }
            $result = yield $articleModel->addArticle($post);
            if ($result){
                $this->success('添加成功！','/article/index');
            }else{
                $this->error('添加失败！');
            }
        }
    }

    /**
     * 文章编辑
     * @param $id integer
     */
    public function actionArticleEdit($id)
    {
        $post = $this->getContext()->getInput()->getAllPost();
        $articleModel = $this->getObject(ArticleModel::class);
        if (empty($post) || $post == false){
            $article         = yield $articleModel->getDetails($id);
            $account         = yield $articleModel->getArticleAccount($id);
            $advertisementModel = $this ->getObject(AdvertisementModel::class);
            $advertisement         = yield $advertisementModel->getLists();
            $categoryModel = $this->getObject(CategoryModel::class);
            $category         = yield $categoryModel->getLists();
            $data['article']       = $article;
            $data['advertisement'] = $advertisement;
            $data['account']       = $account;
            $data['category']       = $category;
            $this->outputView($data);
        }else{
            $upload = $this->getObject(Upload::class);
            try{
                $upload_condition =
                    !empty(
                    $this->getContext()->getInput()->getFile('cover_src')['name']);
            }catch (\Exception $exception){
                $upload_condition =
                    isset($this->getContext()->getInput()->getFile('cover_src')['name']);
            }
            if ( $upload_condition){
                $result = yield $upload->article_upload("cover_src");
                if($result[0]){
                    $filename = $result[1];
                    $true_filename = '/article/'.$filename;
                    $post['cover_src'] = $true_filename;
                }else{
                    $errMsg = yield $upload->getErrorMsg();
                    $this->error($errMsg);
                }
            }
            $result = yield $articleModel->saveArticle($id,$post);
            if ($result){
                $this->success('修改成功！','/article/index');
            }else{
                $this->error('修改失败！');
            }

        }
    }

    /**
     * 删除文章
     * @param $id integer 删除文章的id
     */
    public function actionArticleDelete($id)
    {
        $articleModel = $this->getObject(ArticleModel::class);
        $result       = yield $articleModel->delArticle($id);
        if ($result){
            $this->success('删除成功!');
        }else{
            $this->success('删除失败!');
        }

    }

    /**
     * 文章作者管理
     * @param $id integer 文章id
     */
    public function actionArticleAuthor($id)
    {
        $articleModel = $this->getObject(ArticleModel::class);
        $data       = yield $articleModel->getArticleAccount($id);
        $data['article_id'] = $id;
        $this ->outputView($data);
    }

    /**
     * 删除文章作者
     * @param $id integer 作者id
     * @param $article_id integer 文章id
     */
    public function actionAuthorDelete($id,$article_id)
    {
        $articleModel = $this->getObject(ArticleModel::class);
        $result       = yield $articleModel->delArticleAccount($id);
        if ($result){
            $this->success('删除成功!','/article/articleAuthor?id='.$article_id);
        }else{
            $this->success('删除失败!');
        }

    }

    /**
     * 收藏列表
     */
    public function actionCollection()
    {
        $articleModel = $this->getObject(CollectionModel::class);
        $result = yield $articleModel -> getList();
        //$this ->outputjson(['result' => $result]);return;
        $this ->outputView(['result' => $result]);
    }

    /**
     * 文章收藏
     * @param $id integer 文章id
     */
    public function actionArticleCollectionAdd($id)
    {
        $articleModel = $this->getObject(ArticleModel::class);
        $result = yield $articleModel -> collectionListDetails($id);
        if ($result){
            $this->success('收藏成功!','/article/index');
        }else{
            $this->success('收藏失败!');
        }
    }

    /**
     * 取消文章收藏
     * @param $id integer 文章id
     */
    public function actionArticleCollectionDel($id)
    {
        $CollectionModel = $this->getObject(CollectionModel::class);
        $result = yield $CollectionModel -> delCollection($id);
        if ($result){
            $this->success('取消收藏成功!');
        }else{
            $this->success('取消收藏失败!');
        }
    }

    /**
     * 添加文章作者
     * @param $id integer 作者id
     */
    public function actionAuthorAdd()
    {
        $data = $this ->getContext()->getInput()->getAllPost();
        $articleModel = $this->getObject(ArticleModel::class);
        $result       = yield $articleModel->addArticleAccount($data);
        if ($result){
            $this->success('添加成功!','/article/articleAuthor?id='.$data['article_id']);
        }else{
            $this->success('添加失败!');
        }

    }

    /**
     * 分享设置
     */
    public function actionShare()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        $shareModel = $this ->getObject(ShareModel::class);
        if (!empty($post) && $post != false){
            $upload = $this->getObject(Upload::class);
            if ($this->getContext()->getInput()->getFile('imageurl')['name']){
                $result = yield $upload->article_upload("imageurl");
                if($result[0]){
                    $filename = $result[1];
                    $true_filename = '/article/'.$filename;
                    $post['imageurl'] = $true_filename;
                }else{
                    $errMsg = yield $upload->getErrorMsg();
                    $this->error( $errMsg );
                    return;
                }
            }
            $result = yield $shareModel ->setWechatConfig(json_encode($post));
            if ($result){
                $this->success('设置成功！');
            }else{
                $this->error('设置失败！');
            }
        }else{
            $wx_data = json_decode(yield $shareModel ->getWechatConfig(),true);
            $wx_data['appid'] = $wx_data['appid']?? '';
            $wx_data['secret']= $wx_data['secret'] ?? '';
            $wx_data['title'] = $wx_data['title'] ?? '';
            $wx_data['randhost'] = $wx_data['randhost'] ?? '';
            $wx_data['desc']     = $wx_data['desc'] ?? '';
            $wx_data['imageurl'] = $wx_data['imageurl'] ?? '';
            $wx_data['totalcode'] = $wx_data['totalcode'] ?? '';
            $this->outputView(['data' => $wx_data]);

        }
    }

    /**
     * 富文本编辑器图片上传接口
     */
    public function actionEditUpImageApi()
    {
        $upload = $this->getObject(Upload::class);
        if ($this->getContext()->getInput()->getFile('file')['name']){
            $up_result = yield $upload->article_upload("file");
            if($up_result[0]){
                $filename = $up_result[1];
                $true_filename = '/static/upload/article/'.$filename;
                $result = [
                    'error'=> 0,
                    'msg' => '',
                    'url' => $true_filename,
                    'title' => $filename
                ];
            }else{
                $result = [
                    'error'=> 500,
                    'msg' => yield $upload->getErrorMsg(),
                    'url' => '',
                    'title' => '',
                ];
            }
            $this->outputJson($result);
        }
    }
}
