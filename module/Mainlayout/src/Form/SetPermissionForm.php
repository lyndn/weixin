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
use Zend\Form\Element;

class SetPermissionForm extends Form
{
    public $moduleResultSet;
    public $userPermissionResultSet;

    public function __construct($moduleResultSet = null, $userPermissionResultSet = null)
    {
        parent::__construct('auth');
        $this->moduleResultSet = $moduleResultSet;
        $this->userPermissionResultSet = $userPermissionResultSet;
        $this->SetPermissionForm();
    }

    /**
     * This login Form
     */
    public function SetPermissionForm()
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
                'value' => '设置',
                'id' => 'button',
                'class' => 'sub_button',
            ],
        ]);

        $p = [];
        foreach ($this->userPermissionResultSet as $row)
        {
            $p[] = $row->powerid;
        }

        foreach ($this->moduleResultSet as $row)
        {
            if (!empty($row->permission))
            {
                if(in_array($row->permission,$p)){
                    $attributes =  ['value' => 'yes'];
                }else{
                    $attributes = [];
                }
                $this->add([
                    'type' => Element\Checkbox::class,
                    'name' => $row->permission,
                    'options' => [
                        'label' => $row->modulename,
                        'use_hidden_element' => false,
                        'checked_value' => 'yes',
                        'unchecked_value' => 'no',
                    ],
                    'attributes' => $attributes,
                ]);
            }
        }
    }

}