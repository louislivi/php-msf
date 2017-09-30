<?php
namespace App\Controllers;
use \App\Models\Category as CategoryModel;

/**
 * 栏目管理类
 * Class Category
 * @package App\Controllers
 */
class Category extends AdminAuth
{
    /**
     * 栏目列表
     */
    public function actionIndex()
    {
        $categoryModel = $this ->getObject(CategoryModel::class);
        $data = yield $categoryModel ->getLists();
        $this ->outputView($data);
    }

    /**
     * 添加栏目
     */
    public function actionCategoryAdd()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        $categoryModel = $this->getObject(CategoryModel::class);
        if (empty($post) || $post == false) {
            $data = yield $categoryModel->getParentLists();
            $this->outputView($data);
        }else{
            $result = yield $categoryModel->addCategory($post);
            if ($result){
                $this->success('添加成功！','/category/index');
            }else{
                $this->error('添加失败！');
            }
        }

    }

    /**
     * 编辑栏目
     * @param $id integer 栏目id
     */
    public function actionCategoryEdit($id)
    {
        $post = $this->getContext()->getInput()->getAllPost();
        $categoryModel = $this->getObject(CategoryModel::class);
        if (empty($post) || $post == false) {
            $data = yield $categoryModel->getCategoryDetails($id);
            $list = yield $categoryModel->getLists();
            $data['list'] = $list['result'];
            $this->outputView($data);
        }else{
            $result = yield $categoryModel->editCategory($id,$post);
            if ($result){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！');
            }
        }
    }

    /**
     * 删除栏目
     * @param $id integer 栏目id
     */
    public function actionCategoryDelete($id)
    {
        $categoryModel = $this->getObject(CategoryModel::class);
        $result = yield $categoryModel->deleteCategory($id);
        if ($result){
            $this->success('删除成功！','/category/index');
        }else{
            $this->error('删除失败,请先删除该栏目下的文章！');
        }
    }
}