<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/28
 * Time: 17:03
 */
namespace Fans\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class FansForm extends Form
{
    public function __construct($name=null)
    {
        parent::__construct('fans');
        $this->add([
            'name'=>'remark',
        ]);
        $this->add([
            'name'=>'uid',
            'type'=>'hidden'
        ]);
        $this->add([
            'name'=>'headimgurl'
        ]);
    }
}