<?php
namespace App\Controllers;
use App\Models\Advertisement as AdvertisementModel;
use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\Handlers\Page;
use App\Models\Handlers\Upload;
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
        switch ($this->getContext()->getInput()->get('sort')){
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
        $data['pageList'] = $pageList;
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
            $articleModel = $this->getObject(ArticleModel::class);
            $upload = $this->getObject(Upload::class);
            if ($this->getContext()->getInput()->getFile('cover_src')['name']){
                $result = $upload->article_upload("cover_src");
                if($result){
                    $filename = $upload->getFileName();
                    $true_filename = '/article/'.$filename;
                    $post['cover_src'] = $true_filename;
                }else{
                    $this->error($upload->getErrorMsg());
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
            if ($this->getContext()->getInput()->getFile('cover_src')['name']){
                $result = $upload->article_upload("cover_src");
                if($result){
                    $filename = $upload->getFileName();
                    $true_filename = '/article/'.$filename;
                    $post['cover_src'] = $true_filename;
                }else{
                    $this->error($upload->getErrorMsg());
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
            $result = yield $shareModel ->setWechatConfig(json_encode($post));
            if ($result){
                $this->success('设置成功！');
            }else{
                $this->error('设置失败！');
            }
        }else{
            $wx_data = json_decode(yield $shareModel ->getWechatConfig(),true);
            if (!$wx_data){
                $wx_data['appid']     = '';
                $wx_data['secret'] = '';
            }
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
            $up_result = $upload->article_upload("file");
            if($up_result){
                $filename = $upload->getFileName();
                $true_filename = '/static/upload/article/'.$filename;
                $result = [
                    'code'=> 0,
                    'msg' => '',
                    'data'=> [
                        'src'   => $true_filename,
                        'title' => $filename
                    ]
                ];
            }else{
                $result = [
                    'code'=> 500,
                    'msg' => $upload->getErrorMsg(),
                    'data'=> [
                        'src'   => '',
                        'title' => ''
                    ]
                ];
            }
            $this->outputJson($result);
        }
    }
}