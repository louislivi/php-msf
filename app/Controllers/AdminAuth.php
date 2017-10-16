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
        parent::__construct($controllerName, $methodName);
        $this->assign(['token' => $this->encodeJWT()]);
        if ($this->login_auth() === false){
            throw new \Exception('未登录，非法访问！',10086);
        }
        if ($this->form_auth()  === false){
            throw new \Exception('token验证失败！',10086);
        }
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
                $this->error('登录已失效！','/admin/login');
                return false;
            }
        }else{
            $this->error('您尚未登录！','/admin/login');
            return false;
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
                return false;
            }

        }
    }
}