<?php
/**
 *
 * PHP Version ～7.1
 * @package   loginForm.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 12:47
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
 

namespace Mainlayout\Form;
use Zend\Debug\Debug;
use Zend\Form\Form;
use Zend\Form\Element;

class AddRoleForm extends Form
{
    public $wechatName;
    public function __construct($wechatName = [])
    {
        parent::__construct('auth');
        $this->wechatName = $wechatName;
        $this->addroleForm();
    }

    /**
     * This login Form
     */
    public function addroleForm()
    {
        $this->add([
            'name' => 'roletitle',
            'type' => 'text',
        ]);

        $this->add([
            'name'=>'wechatid',
            'type' => Element\Select::class,
            'options'=>[
                'value_options' => $this->wechatName
            ]
        ]);

        $this->add([
            'name' => 'cancel',
            'type' => 'submit',
            'attributes' => [
                'value' => '取消',
                'onmousemove' => 'this.className=\'input_move\'',
                'onmouseout' => 'this.className=\'input_out\'',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => '提交',
                'onmousemove' => 'this.className=\'input_move\'',
                'onmouseout' => 'this.className=\'input_out\'',
            ],
        ]);
    }
}