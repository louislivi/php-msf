<?php
namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use \Firebase\JWT\JWT;

/**
 * 后台管理基类
 * Class AdminBase
 * @package App\Controllers
 */
class AdminBase extends Controller
{
    static private $key = 'my';

    /**
     *
    sub: jwt所面向的用户
    aud: 接收jwt的一方
    exp: jwt的过期时间，这个过期时间必须要大于签发时间
    nbf: 定义在什么时间之前，该jwt都是不可用的.
    iat: jwt的签发时间
    jti: jwt的唯一身份标识，主要用来作为一次性token,从而回避重放攻击。
     */
    static private $token = array(
        "username" => "123456",
        "password" => "123456",
        "iss" => "http://192.168.92.129",
        "aud" => "http://192.168.92.129",
        "iat" => 0,
        "nbf" => 0
    );

    public function __construct($controllerName, $methodName)
    {
        self::$token['iat'] = time();
        self::$token['nbf'] = time();
        self::$token['exp'] = time()+3600;
        parent::__construct($controllerName, $methodName);
    }

    /**
     * 成功跳转
     * @param $message string 跳转信息
     */
    protected function success($message,$redirect_url = null)
    {
        $this->outputView(
            [
                'message' => $message,'redirect_url' => $redirect_url
            ],'Admin/Success');
        throw new \Exception('成功，页面关闭！',10086);
    }

    /**
     * 失败跳转
     * @param $message string 跳转信息
     */
    protected function error($message,$redirect_url = null)
    {
        $this->outputView(
            [
                'message' => $message,'redirect_url' => $redirect_url
            ],'Admin/Error');
        throw new \Exception('失败，页面关闭！',10086);
    }

    /**
     * 生成JWT
     */
    protected function encodeJWT()
    {
        return JWT::encode(self::$token, self::$key);
    }

    /**
     * 验证JWT
     * @param $jwt string token
     * @param $key string 秘钥
     * @return object
     */
    protected function checkJWT($token = '')
    {
        //$this->output($token);return;
        try{
            $token = trim($token);
            if ( $token == '' ) {
                $token = $this->getContext()->getInput()->getPost('__RequestVerificationToken');
            }
            $json = JWT::decode($token,self::$key,array('HS256'));
        }catch(\Exception $exception){
            $json = [];
        }
        return $json;
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