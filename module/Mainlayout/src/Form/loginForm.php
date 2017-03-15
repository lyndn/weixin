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

class loginForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('auth');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => '用户名',
            ],
        ]);
        $this->add([
            'name' => 'artist',
            'type' => 'text',
            'options' => [
                'label' => '密码',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => '阿斯顿',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}