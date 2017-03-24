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


class ResetPasswdForm extends Form
{
    public function __construct($roleResult = null)
    {
        parent::__construct('account');
        $this->adduserForm();
    }

    /**
     * This add user Form
     */
    public function adduserForm()
    {
        //密码
        $password = new Element\Password('password');
        $password->setAttributes([
            'size'  => '30',
        ]);
        $this->add($password);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => '修改',
                'onmousemove' => 'this.className=\'input_move\'',
                'onmouseout' => 'this.className=\'input_out\'',
            ],
        ]);
    }


}