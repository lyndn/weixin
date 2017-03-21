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
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('auth');
        $this->loginForm();
    }

    /**
     * This login Form
     */
    public function loginForm()
    {
        $this->add([
            'name' => 'username',
            'type' => 'text',
            'attributes' => [
                'class' => 'txt_input txt_input2',
            ],
        ]);
        $this->add([
            'name' => 'passwd',
            'type' => 'text',
            'attributes' => [
                'class' => 'txt_input',
            ],
        ]);
        $this->add([
            'name' => 'isSavePwd',
            'type' => 'checkbox',
            'attributes' => [
                'id' => 'save_me',
            ]
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => '登录',
                'id'    => 'button',
                'class' => 'sub_button',
                'style' => 'opacity: 0.7',
            ],
        ]);
    }

}