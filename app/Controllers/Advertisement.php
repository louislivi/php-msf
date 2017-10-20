<?php
namespace App\Controllers;

use \App\Models\Advertisement as AdvertisementModel;
use App\Models\Handlers\Page;
use \App\Tasks\Upload;

/**
 * 广告管理类
 * Class Advertisement
 * @package App\Controllers
 */
class Advertisement extends AdminAuth
{
    /**
     * 广告列表
     */
    public function actionIndex()
    {
        $advertisementModel = $this ->getObject(AdvertisementModel::class);
        $total = yield $advertisementModel->getTotal();
        $page = $this ->getObject(Page::class,[$total]);
        $limit = $page ->limit;
        $data  = yield $advertisementModel ->getDetailsLists($limit);
        $pageList = $page ->fpage();
        $data['pageList'] = $pageList;
        $this ->outputView($data);
    }

    /**
     * 首页广告设置
     */
    public function actionHome()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        $advertisementModel = $this ->getObject(AdvertisementModel::class);
        if (!isset($post['index_adver_set']) || $post == false) {
            $data = yield $advertisementModel->getHomeSetting();
            if ($data){
                $data = implode('|',json_decode($data,true));
                $this ->outputView(['index_adver_set'=>$data]);
            }else{
                $this ->outputView(['index_adver_set'=>'']);
            }

        }else{
            if ($post['index_adver_set']){
                $value = json_encode(explode('|',$post['index_adver_set']));
            }else{
                $value = '[]';
            }
            $result = yield $advertisementModel->setHomeSetting($value);
            if ($result){
                $this->success('修改成功！','/advertisement/home');
            }else{
                $this->error('修改失败！');
            }
        }
    }

    /**
     * 添加广告
     */
    public function actionAdvertisementAdd()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        if (empty($post) || $post == false) {
            $this->outputView([]);
        }else{
            $advertisementModel = $this->getObject(AdvertisementModel::class);
            $upload = $this->getObject(Upload::class);
            if ($this->getContext()->getInput()->getFile('cover_src')['name']){
                $result = yield $upload->advertisement_upload("cover_src");
                if($result[0]){
                    $filename = $result[1];
                    $true_filename = '/advertisement/'.$filename;
                    $post['cover_src'] = $true_filename;
                }else{
                    $errMsg = yield $upload->getErrorMsg();
                    $this->error($errMsg);
                }
            }
            $result = yield $advertisementModel->addAdvertisement($post);
            if ($result){
                $this->success('添加成功！','/advertisement/index');
            }else{
                $this->error('添加失败！');
            }
        }

    }

    /**
     * 编辑广告
     * @param $id integer 栏目id
     */
    public function actionAdvertisementEdit($id)
    {
        $post = $this->getContext()->getInput()->getAllPost();
        $advertisementModel = $this->getObject(AdvertisementModel::class);
        if (empty($post) || $post == false) {
            $data = yield $advertisementModel->getAdvertisementDetails($id);
            $this->outputView($data);
        }else{
            $upload = $this->getObject(Upload::class);
            if ($this->getContext()->getInput()->getFile('cover_src')['name']){
                $result = yield $upload->advertisement_upload("cover_src");
                if($result[0]){
                    $filename = $result[1];
                    $true_filename = '/advertisement/'.$filename;
                    $post['cover_src'] = $true_filename;
                }else{
                    $errMsg = yield $upload->getErrorMsg();
                    $this->error($errMsg);
                }
            }
            $result = yield $advertisementModel->editAdvertisement($id,$post);
            if ($result){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！');
            }

        }
    }

    /**
     * 删除广告
     * @param $id integer 栏目id
     */
    public function actionAdvertisementDelete($id)
    {
        $advertisementModel = $this->getObject(AdvertisementModel::class);
        $result = yield $advertisementModel->deleteAdvertisement($id);
        if ($result){
            $this->success('删除成功！','/advertisement/index');
        }else{
            $this->error('删除失败,请先删除该栏目下的文章！');
        }
    }
}