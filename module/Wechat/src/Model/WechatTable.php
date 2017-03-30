<?php
namespace Wechat\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


class WechatTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //获取数据
    public function fetchAll($paginated = false,$where=null)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults($where);
        }
        return $this->tableGateway->select($where);
    }

    //保存数据
    public function saveWechat(Wechat $form)
    {
        $data = [
            'wxname' => $form->wxname,
            'wxid'  => $form->wxid,
            'weixin'  => $form->weixin,
            'appid'  => $form->appid,
            'appsecret'  => $form->appsecret,
            'headerpic'  => $form->headerpic,
            'qrcode'  => $form->qrcode,
            'typeid'  => $form->typeid,
            'token'  => $form->token,
            'typename'=>$this->typename($form->typeid),
            'EncodeType'=>$form->EncodeType,
            'AesEncodingKey'=>$form->AesEncodingKey,
        ];

        $id = (int) $form->id;
        if ($id === 0) {
            //需补增
            $serUrl='';
            $data['addtime']=$form->addtime;
            $data['uid']=$form->uid;
            $data['operId']=$form->operId;
            return $this->tableGateway->insert($data);
        }

        if (! $this->getWechat($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

       return $this->tableGateway->update($data, ['id' => $id]);
    }

    //查看
    public function getWechat($id){
        $id=(int)$id;
        $query=$this->tableGateway->select(['id'=>$id]);
        $row=$query->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
        return $row;
    }

    //删除
    public function deleteWechat($id)
    {
        return $this->tableGateway->delete(['id' => (int) $id]);
    }

    //数据分页
    private function fetchPaginatedResults($where=null)
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());
        if($where){
            $select->where($where);
        }
        $select->order("id desc");
        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Wechat());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
        // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }
    private function typename($id){
        return $id==1?'认证订阅号':'认证服务号';
    }

    //获取微信公众号配置
    public function wxconfig($id,$debug=false,$level='debug',$log='log/easywechat.log'){
        $wxuser=$this->getWechat($id);
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
}