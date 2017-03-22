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
            'AesEncodingKey'=>$form->AesEncodingKey
        ];

        $id = (int) $form->id;
        if ($id === 0) {
            //需补增
            $serUrl='';
            $data['addtime']=$form->addtime;
            $data['uid']=$form->uid;
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
}