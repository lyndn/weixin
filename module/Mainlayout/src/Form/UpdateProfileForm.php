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
use Zend\Form\Element;
use Zend\Form\Form;


class UpdateProfileForm extends Form
{
    public $roleResult;
    public function __construct($roleResult = null)
    {
        parent::__construct('account');
        $this->roleResult = $roleResult;
        $this->adduserForm();
    }


    /**
     * This add user Form
     */
    public function adduserForm()
    {
        //姓名
        $this->add([
            'name' => 'realname',
            'type' => 'text',
        ]);

        //用户名
        $this->add([
            'name' => 'username',
            'type' => 'text',
        ]);


        $role = [];
        foreach ($this->roleResult as $row)
        {
            $role[$row->roleid] = $row->roletitle;
        }
        //角色
        $this->add([
            'name'=>'role',
            'type' => Element\Select::class,
            'options'=>[
                'value_options' => $role
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