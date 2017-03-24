<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 14:16
 */
namespace Wechat\Controller;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\News;
use Wechat\Model\WechatTable;
use Zend\Mvc\Controller\AbstractActionController;
use EasyWeChat\Foundation\Application;

class ServerController extends AbstractActionController
{
    private $table;
    public function __construct(WechatTable $table)
    {
        $this->table=$table;
    }
    //配置微信
    private function wxconfig($id,$debug=false,$level='debug',$log='log/easywechat.log'){
        $wxuser=$this->table->getWechat($id);
        $config= [
            /**
             * Debug 模式，bool 值：true/false
             *
             * 当值为 false 时，所有的日志都不会记录
             */
            'debug'  => $debug,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => $wxuser->appid,          // AppID
            'secret'  => $wxuser->appsecret,      // AppSecret
            'token'   => $wxuser->token,          // Token
            'aes_key' => $wxuser->AesEncodingKey, // EncodingAESKey，安全模式下请一定要填写！！！
            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
             * file：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'level'      => $level,
                'permission' => 0777,
                'file'       => $log,
            ],
            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/examples/oauth_callback.php',
            ],
            /**
             * 微信支付
             */
            'payment' => [
                'merchant_id'        => $wxuser->mch_id,
                'key'                => $wxuser->key,
                'cert_path'          => $wxuser->cert_path, // XXX: 绝对路径！！！！
                'key_path'           => $wxuser->key_path,      // XXX: 绝对路径！！！！
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
            /**
             * Guzzle 全局设置
             *
             * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
             */
            'guzzle' => [
                'timeout' => 3.0, // 超时时间（秒）
                //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ],
        ];
        return $config;
    }
    //消息类型分离
    private function message($message)
    {
        switch ($message->MsgType) {
            case 'event':
                return '收到事件消息';
                break;
            case 'text':
                return '收到文字消息'.$message->FromUserName;
                break;
            case 'image':
                return '收到图片消息';
                break;
            case 'voice':
                return '收到语音消息';
                break;
            case 'video':
                return '收到视频消息';
                break;
            case 'location':
                return '收到坐标消息';
                break;
            case 'link':
                return '收到链接消息';
                break;
            // ... 其它消息
            default:
                return '收到其它消息';
                break;
        }
    }
    public function indexAction()
    {
        $id=(int) $this->params()->fromRoute('id',0);
        if($id){
            $config=$this->wxconfig($id);
            $app = new Application($config);
            $server=$app->server;
            $server->setMessageHandler(function($message){
                return $this->message($message);
            });
            $response=$server->serve();
            // 将响应输出
            $response->send(); // Laravel 里请使用：return $response;
            exit;
        }else{
            echo '参数错误';
            exit;
        }
    }
}