<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 13:49
 */
namespace Wechat\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class WechatForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('wechat');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name'=>'uid',
            'type'=>'hidden'
        ]);
        $this->add([
            'name' => 'wxname',
            'type' => 'text',
            /*选项
             * 'options' => [
                'label' => 'Title',
            ],*/
            /*
             * 表单属性
            */
            'attributes'=>array(
                'id'=>'wxname',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'微信公众号名称',
                'class'=>'px',
                'size'=>25
            )
        ]);
        $this->add([
            'name' => 'wxid',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'wxid',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'微信号原始ID',
                'class'=>'px',
                'size'=>25,
                'onmouseup'=>"this.value=this.value.replace('_430','')"
            )
        ]);
        $this->add([
            'name' => 'weixin',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'weixin',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'微信号',
                'class'=>'px',
                'size'=>25
            )
        ]);
        $this->add([
            'name' => 'headerpic',
            'type' => 'file',
            'attributes'=>array(
                'id'=>'headerpic',
                'autofocus'=>true,
                'placeholder'=>'图像',
                'class'=>'px',
                'size'=>25
            )
        ]);
        $this->add([
            'name' => 'appid',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'appid',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'appid',
                'class'=>'px',
                'size'=>25
            )
        ]);
        $this->add([
            'name' => 'appsecret',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'appsecret',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'appsecret',
                'class'=>'px',
                'size'=>25
            )
        ]);
        $this->add([
            'name' => 'qrcode',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'qrcode',
                'autofocus'=>true,
                'placeholder'=>'qrcode',
                'class'=>'px',
                'size'=>25
            )
        ]);


        $this->add([
            'name' => 'token',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'token',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'token',
                'class'=>'px',
                'size'=>25
            )
        ]);

        $this->add([
            'name'=>'typeid',
            'type' => Element\Select::class,
            'options'=>[
                'value_options' => [
                    '1' => '认证订阅号',
                    '2' => '认证服务号',
                ],
            ]
        ]);

        $this->add([
            'name' => 'EncodeType',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'EncodeType',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'EncodeType',
                'class'=>'px',
                'size'=>25
            )
        ]);
        $this->add([
            'name' => 'AesEncodingKey',
            'type' => 'text',
            'attributes'=>array(
                'id'=>'AesEncodingKey',
                'autofocus'=>true,
                'required'=>true,
                'placeholder'=>'AesEncodingKey',
                'class'=>'px',
                'size'=>25
            )
        ]);

        $this->add([
            'name'=>'submit',
            'type'=>'submit',
            'attributes' => [
                'value' => '保存',
                'id'    => 'saveSetting',
                'class' => 'btnGreen',
            ],
        ]);
    }
}