<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/24
 * Time: 13:57
 */
namespace Wechat\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Validator\StringLength;

class WxmenuForm extends Form
{
    public function __construct($name=null)
    {
        parent::__construct('wxmenu');

        $this->add([
            'name'=>'id',
            'type'=>'hidden'
        ]);
        $this->add([
            'name'=>'wxid',
            'type'=>'hidden'
        ]);
        $this->add([
            'name'=>'title',
            'type'=>'text',
            'attributes'=>[
                'id'=>'title',
                'required'=>true,
            ],
        ]);
        $this->add([
            'type' => Element\Radio::class,
            'name' => 'is_show',
            'attributes'=>[
                'id'=>'is_show',
                'required'=>true,
            ],
            'options' => [
                'value_options' => [
                    '1' => '是',
                    '0' => '否',
                ],
            ],
        ]);
        $this->add([
            'name'=>'parentId',
        ]);
        $this->add([
            'name'=>'operId',
        ]);
        $this->add([
            'name'=>'keyword',
            'type'=>'text',
        ]);
        $this->add([
            'name'=>'sort',
            'type'=>'text',
        ]);
        $this->add([
            'name'=>'url',
            'type'=>'text',
        ]);
        $this->add([
            'name'=>'wxsys',
            'type'=>Element\Select::class,
            'options'=>[
                'value_options'=>[
                    '扫码带提示'=>'扫码带提示',
                    '扫码推事件'=>'扫码推事件',
                    '系统拍照发图'=>'系统拍照发图',
                    '拍照或者相册发图'=>'拍照或者相册发图',
                    '微信相册发图'=>'微信相册发图',
                    '发送位置'=>'发送位置',
                ],
            ],
        ]);
        $this->add([
            'name'=>'content'
        ]);
        $this->add([
            'name'=>'type',
            'type' => Element\Select::class,
            'options'=>[
                'value_options' => [
                    '1' => '关键词回复菜单',
                    '2' => 'url链接菜单',
                    '3' => '微信扩展菜单',
                    '4' => '一键拨号菜单',
                    '5' => '一键导航',
                ],
            ],
            'attributes'=>array(
                'id'=>'type'
            )
        ]);
        $this->add([
            'name'=>'tel',
            'type'=>'text',
        ]);
        $this->add([
            'name'=>'longitude'
        ]);
        $this->add([
            'name'=>'latitude'
        ]);
        $this->add([
            'name'=>'nav'
        ]);
    }
}