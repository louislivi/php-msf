<?php
namespace App\Controllers;

/**
 * 后台首页类
 * Class Home
 * @package App\Controllers
 */
class Home extends AdminAuth
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        $this->outputView([]);
    }

    /**
     * 欢迎页面
     */
    public function actionMain()
    {
        $this->outputView([]);
    }

    /**
     * 分享設置
     */
}