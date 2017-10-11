<?php
namespace App\Controllers;

/**
 * 后台管理登入登出类
 * Class Admin
 * @package App\Controllers
 */
class Admin extends AdminBase
{

    /**
     * 登录页面
     */
    public function actionLogin()
    {
        $jwt = $this->encodeJWT();
        $this->outputView(['token'=>$jwt]);
    }

    /**
     * 登出
     */
    public function actionLogout()
    {
        $this->getContext()->getOutput()
            ->setCookie('username',NULL,-1);
        $this->getContext()->getOutput()
            ->setCookie('token',NULL,-1);
        $this->success('已退出登录！','/admin/login');
    }

    /**
     * 验证token及用户名密码
     */
    public function actionCheck()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        if (!empty($post) && $post != false){
            $verify_result = $this->checkJWT();
            if ($verify_result){
                if ($post['username'] == 'admin' && $post['password'] == 'onetwo666'){
                    $this->getContext()->getOutput()
                        ->setCookie('username',$post['username']);
                    $this->getContext()->getOutput()
                        ->setCookie('token',$post['__RequestVerificationToken']);
                    $this->success('登录成功','/home/index');
                }else{
                    $this->error('账号或密码错误！');
                }
            }else{
                $this->error('token已失效，请刷新页面！');
            }
        }else{
            $this->error('请求方式错误或参数错误！');
        }
    }


}