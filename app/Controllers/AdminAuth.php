<?php
namespace App\Controllers;

/**
 * 后台验证类
 * Class AdminAuth
 * @package App\Controllers
 */
class AdminAuth extends AdminBase
{
    public function __construct($controllerName, $methodName)
    {
        $this->assign(['token' => $this->encodeJWT()]);
        $this->login_auth();
        $this->form_auth();
        parent::__construct($controllerName, $methodName);
    }

    /**
     * 登录验证
     */
    private function login_auth()
    {
        $username = $this->getContext()->getInput()->getCookie('username');
        $token = $this->getContext()->getInput()->getCookie('token');
        if ($username){
            try{
                $this->checkJWT($token);
            }catch(\Exception $exception){
                $this->error('登录已失效！');
            }
        }else{
            $this->error('您尚未登录！');
        }

    }

    /**
     * 表单验证
     */
    private function form_auth()
    {
        $post = $this->getContext()->getInput()->getAllPost();
        if (!empty($post) && $post != false){
            if($this->checkJWT()){
                unset($post['__RequestVerificationToken']);
                $this->getContext()->getInput()->setAllPost($post);
            }else{
                $this->error('token验证失败！');
            }

        }
    }
}