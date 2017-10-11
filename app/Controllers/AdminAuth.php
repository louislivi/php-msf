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
                $this->error('登录已失效！');
                return false;
            }
        }else{
            $this->error('您尚未登录！');
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

    /**
     * 异常的回调
     *
     * @param \Throwable $e 异常实例
     * @throws \Throwable
     */
    public function onExceptionHandle(\Throwable $e)
    {
        try {
            if ($e->getCode() != 10086){
                if ($e->getPrevious()) {
                    $ce     = $e->getPrevious();
                    $errMsg = dump($ce, false, true);
                } else {
                    $errMsg = dump($e, false, true);
                    $ce     = $e;
                }
                $this->getContext()->getLog()->error($errMsg);
                $this->output('Internal Server Error', 500);
            }
        } catch (\Throwable $ne) {
            getInstance()->log->error('previous exception ' . dump($ce, false, true));
            getInstance()->log->error('handle exception ' . dump($ne, false, true));
        }
    }
}