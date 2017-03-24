<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 14:37
 */
namespace Wechat\Controller;

use Wechat\Form\UploadForm;
use Wechat\Model\WxmenuTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Mainlayout\Controller\MainlayoutController;
use Wechat\Model\Wxmenu;
use Mainlayout\Model\MyRole;
use Wechat\Model\WechatTable;

class FunctionController extends AbstractActionController
{
    private $table;
    private $myrole;
    private $wxuser;

    public function __construct(WxmenuTable $table,WechatTable $wxuser,MyRole $myRole)
    {
        $this->table=$table;
        $this->wxuser=$wxuser;
        $this->myrole=$myRole;
    }
    public function indexAction(){
        return [];
    }
    public function menuAction()
    {
        $view=new ViewModel(['form'=>'aaa']);
        $view->setTemplate('Wechat/function/menu');
        return $view;
    }
}