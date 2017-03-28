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
use Zend\Debug\Debug;

class WxmenuTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //获取菜单列表
    public function getMenu($wxid=0,$parentid=0,$del=0){

        return $this->tableGateway->select(['wxid'=>$wxid,'parentId'=>$parentid,'del'=>$del]);
    }
    //通过ID获取
    public function getMenuInfo($id){
        $id=(int)$id;
        $query=$this->tableGateway->select(['id'=>$id]);
        $row=$query->current();
        if (! $row) {
            return false;
        }
        return $row;
    }
    //获取两级菜单列表
    public function getMenuRows($wxid=0){
        $menu='';
        $menu1=$this->getMenu($wxid);
        foreach($menu1 as $k=>$v){
            $menu->$k=$v;
            $type=$v->type;
            $menu->$k->type=$this->typename($type);
            $menu->$k->val=$this->field($type,$v);
            foreach($this->getMenu($wxid,$v->id) as $key=>$val){
                $menu->$k->nav->$key=$val;
                $type=$val->type;
                $menu->$k->nav->$key->type=$this->typename($type);
                $menu->$k->nav->$key->val=$this->field($type,$val);
            }
        }
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
            'keyword'=>$form->keyword
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
    //删除
    public function deleteMenu($id){
        return $this->tableGateway->delete(['id'=>$id]);
    }
    //生成菜单
    public function craeteMenu($wxid){
        $nav=$this->tableGateway->select(['wxid'=>$wxid,'parentId'=>0,'is_show'=>1,'del'=>0]);
        $button=array();
        foreach($nav as $k=>$v){
            $button[$k]=array("name" => $v->title);
            $navs=$this->tableGateway->select(['wxid'=>$wxid,'parentId'=>$v->id,'is_show'=>1,'del'=>0]);
            unset($navlist);
            foreach ($navs as $rows){
                $navlist[]=$rows;
            }
            if(count($navlist)){
                foreach($navlist as $key=>$val){
                    $button[$k]['sub_button'][$key]=array(
                        'type'=>$this->menuType($val),
                        'name'=>$val->title,
                    );
                    if($val->type==1){
                        $button[$k]['sub_button'][$key]['key']=$val->keyword;
                    }
                    if($val->type==2){
                        $button[$k]['sub_button'][$key]['url']=$val->url;
                    }
                    if($val->type==3){
                        $button[$k]['sub_button'][$key]['key']='rselfmenu_'.$k.'_'.$key;
                    }
                    if($val->type==4){
                        $button[$k]['sub_button'][$key]['key']=$val->tel;
                    }
                    if($val->type==5){
                        $button[$k]['sub_button'][$key]['key']=$val->nav;
                    }
                }
            }else{
                $button[$k]['type']=$this->menuType($v);
                if($v->type==1){
                    $button[$k]['key']=$v->keyword;
                }
                if($v->type==2){
                    $button[$k]['url']=$v->url;
                }
                if($v->type==3){
                    $button[$k]['key']='rselfmenu_'.$k.'_0';
                }
                if($v->type==4){
                    $button[$k]['key']=$v->tel;
                }
                if($v->type==5){
                    $button[$k]['key']=$v->nav;
                }
            }
        }
        return $button;
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
    public function field($type,$obj){
        switch ($type){
            case 1:
                return $obj->keyword;
                break;
            case 2:
                return $obj->url;
                break;
            case 3:
                return $obj->wxsys;
                break;
            case 4:
                return $obj->tel;
                break;
            case 5:
                return $obj->nav;
                break;
            default:
                break;
        }
    }

    //微信菜单触发类型转换
    public function menuType($obj){
        $type=$obj->type;
        switch ($type){
            case 1:
                return 'click';
                break;
            case 2:
                return 'view';
                break;
            case 3:
                return $this->wxsysType($obj->wxsys);
                break;
            default:
                return 'click';
                break;
        }
    }
    public function wxsysType($key){
        $wxsys 	= array(
            '扫码带提示'=>'scancode_waitmsg',
            '扫码推事件'=>'scancode_push',
            '系统拍照发图'=>'pic_sysphoto',
            '拍照或者相册发图'=>'pic_photo_or_album',
            '微信相册发图'=>'pic_weixin',
            '发送位置'=>'location_select',
        );
        return $wxsys[$key];
    }
}