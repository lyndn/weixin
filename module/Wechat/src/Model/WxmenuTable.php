<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 15:06
 */
namespace Wechat\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class WxmenuTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //获取菜单列表
    public function getMenu($wxid=0,$parentid=0){

        return $this->tableGateway->select(['wxid'=>$wxid,'parentId'=>$parentid]);
    }
    //获取两级菜单列表
    public function getMenuRows($wxid=0){
        $menu='';
        $menu1=$this->getMenu($wxid);
        foreach($menu1 as $k=>$v){
            $menu->$k=$v;
            $type=$v->type;
            $menu->$k->type=$this->typename($type);
            if($type==2){
                $menu->$k->val=$v->url;
            }
            var_dump($menu->$k->val);
            exit;
            foreach($this->getMenu($wxid,$v->id) as $key=>$val){
                $menu->$k->nav->$key=$val;
                $menu->$k->nav->$key->type=$this->typename($val->type);
                //$menu->$k->nav->$key->val=$this->field($val->type);
            }
        }
        var_dump($menu);
        exit;
        return $menu;
    }
    //保存数据
    public function saveMenu(Wxmenu $form)
    {
        $data=[
            'title'=>$form->title,
            'wxid'=>$form->wxid,
            'parentId'=>$form->parentId,
            'is_show'=>$form->is_show,
            'sort'=>$form->sort,
            'url'=>$form->url,
            'wxsys'=>$form->wxsys,
            'content'=>$form->content,
            'type'=>$form->type,
            'tel'=>$form->tel,
            'nav'=>$form->nav,
        ];
        $id=(int)$form->id;
        if($id){
           return $this->tableGateway->update($data,['id'=>$id]);
        }else{
           return $this->tableGateway->insert($data);
        }
    }
    //判断数据已经有多少
    public function countMenu($wxid,$parentId=0){
        $select=new Select();
        $select->from($this->tableGateway->getTable());
        $select->where(['wxid'=>$wxid,'parentId'=>$parentId]);
        $db=new DbSelect($select,$this->tableGateway->getAdapter());
        return $db->count();
    }
    //类型转换
    private function typename($type){
        $type_arr=[
            '1'=>'关键词回复菜单',
            '2'=>'url链接菜单',
            '3'=>'微信扩展菜单',
            '4'=>'一键拨号菜单',
            '5'=>'一键导航'
        ];
        return $type_arr[$type];
    }
    //字段转换
    public function field($type){
        $field_arr=[
            '1'=>'keyword',
            '2'=>'url',
            '3'=>'wxsys',
            '4'=>'tel',
            '5'=>'nav'
        ];
        return $field_arr[$type];
    }
}