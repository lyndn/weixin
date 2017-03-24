<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 14:37
 */
namespace Wechat\Controller;

use Wechat\Form\UploadForm;
use Wechat\Form\WxmenuForm;
use Wechat\Model\WxmenuTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Mainlayout\Controller\MainlayoutController;
use Wechat\Model\Wxmenu;
use Mainlayout\Model\MyRole;
use Wechat\Model\WechatTable;
use Interop\Container\ContainerInterface;

class FunctionController extends AbstractActionController
{
    private $table;
    private $myrole;
    private $wxuser;
    private $serviceManager;

    public function __construct(WxmenuTable $table,WechatTable $wxuser,MyRole $myRole,ContainerInterface $serviceManager)
    {
        $this->table=$table;
        $this->wxuser=$wxuser;
        $this->myrole=$myRole;
        $this->serviceManager=$serviceManager;
    }
    public function indexAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        $view=new ViewModel(['id'=>$id]);
        $view->setTerminal(true);
        $view->setTemplate("Wechat/function/index");
        return $view;
    }
    public function menuAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id){
            $form=new WxmenuForm();
            $mywx=$this->wxuser->getWechat($id);
            $menuRows=$this->table->getMenuRows($id);
            $temp=['mywx'=>$mywx,'menuRows'=>$menuRows,'form'=>$form];
            $view=new ViewModel($temp);
            $view->setTerminal(true);
            $view->setTemplate('Wechat/function/menu');
            return $view;
        }else{
           return $this->redirect()->toRoute('wechat');
        }
    }
    //添加
    public function savemenuAction()
    {
        $id=$this->params()->fromPost('id');
        $request = $this->getRequest();
        $form=new WxmenuForm();
        $wxmenu=new Wxmenu();
        $form->setInputFilter($wxmenu->getInputFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("表单验证失败！");history.go(-1);');
        }
        $data=$form->getData();
        $count=$this->table->countMenu($data['wxid'],$data['parentId']);
        if($data['parentId'] && $count>=5){
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("二级栏目不得超过5个");history.go(-1);');
        }else if($count>=3){
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("一级栏目不得超过3个！");history.go(-1);');
        }
        switch ($data['type']){
            case 1:
                unset($data['url']);
                unset($data['wxsys']);
                unset($data['tel']);
                unset($data['nav']);
                break;
            case 2:
                unset($data['keyword']);
                unset($data['wxsys']);
                unset($data['tel']);
                unset($data['nav']);
                break;
            case 3:
                unset($data['url']);
                unset($data['keyword']);
                unset($data['tel']);
                unset($data['nav']);
                break;
            case 4:
                unset($data['url']);
                unset($data['wxsys']);
                unset($data['keyword']);
                unset($data['nav']);
                break;
            case 5:
                unset($data['url']);
                unset($data['wxsys']);
                unset($data['tel']);
                unset($data['keyword']);
                $data['nav']=$data['longitude'].','.$data['latitude'];
                break;
            default:
                break;
        }
        $wxmenu->exchangeArray($form->getData());
        $res=$this->table->saveMenu($wxmenu);
        if($res){
            return $this->redirect()->toRoute('function',['action'=>'menu','id'=>$data['wxid']]);
        }else{
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("保存失败！");history.go(-1);');
        }
    }

}