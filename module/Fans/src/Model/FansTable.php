<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/28
 * Time: 17:04
 */
namespace Fans\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


class FansTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //创建表
    public function createTable($tabName)
    {
        if($tabName){
                //此处已写死，创建复制fans表
                $sql="CREATE TABLE $tabName LIKE fans;";
                $db=$this->tableGateway->getAdapter();
                $db->query($sql)->execute();
                $sql2="insert into fans_table (tab_name) VALUES ('".$tabName."')";
                $db->query($sql2)->execute();
                return true;
        }else{
            return false;
        }
    }
    //判断表是否存在
    public function showTable($tabName)
    {
        $db=$this->tableGateway->getAdapter();
        $res=$db->query("SHOW TABLES LIKE '$tabName'")->execute();
        return $res->count();
    }
    //联合表
    public function unionTable()
    {
        $union_tab='fans';
        $Adapter=$this->tableGateway->Adapter;
        $db=new TableGateway('fans_table',$Adapter);
        $res=$db->select();
        foreach($res as $row){
            if($this->showTable($row->tab_name)){
                $union_tab.=",".$row->tab_name;
            }
        }
        $Adapter->query("ALTER TABLE ".$this->tableGateway->getTable()." UNION =($union_tab)")->execute();
    }
    //创建ID
    public function createUid($data){
        $Adapter=$this->tableGateway->Adapter;
        $db=new TableGateway('fans_uid',$Adapter);
        $b=$db->select($data);
        if(!$b->count()){
            $db->insert($data);
            return $db->getLastInsertValue();
        }else{
            return  $b->current()->id;
        }
    }

    //插入数据(对应相应的表)
    public function insertFans($tabName,$data){
        $Adapter=$this->tableGateway->Adapter;
        $db=new TableGateway($tabName,$Adapter);
        $res=$db->select(['uid'=>$data['uid']]);
        if($res->count()){
            $db->update($data,['uid'=>$data['uid']]);
            return $data['uid'];
        }else {
            $db->insert($data);
            return $db->getLastInsertValue();
        }
    }

    //更新数据
    public function updateFans($data){
        return $this->tableGateway->update($data,['uid'=>$data['uid']]);
    }

    //获取单个粉丝信息
    public function getFans($uid){
        $query=$this->tableGateway->select(['uid'=>$uid]);
        $row=$query->current();
        if($row){
            return $row;
        }else{
            return false;
        }
    }
    //获取粉丝列表
    public function listFans($paginated=false,$where=null){
        if ($paginated) {
            return $this->fetchPaginatedResults($where);
        }
        return $this->tableGateway->select($where);
    }

    //数据分页
    private function fetchPaginatedResults($where=null)
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());
        if($where){
            $select->where($where);
        }
        $select->order("subscribe_time desc");

        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Fans());

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
}