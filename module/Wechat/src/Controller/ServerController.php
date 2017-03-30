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
    //消息类型分离
    private function message($message)
    {
        switch ($message->MsgType) {
            case 'event':
                return '收到事件消息';
                break;
            case 'text':
                return '收到文字消息--'.$message->ToUserName;
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
            $config=$this->table->wxconfig($id);
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