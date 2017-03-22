<?php
namespace Wechat\Controller;

use Wechat\Form\UploadForm;
use Wechat\Model\WechatTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Mainlayout\Controller\MainlayoutController;
use Wechat\Form\WechatForm;
use Wechat\Model\Wechat;
use Mainlayout\Model\MyRole;

class IndexController extends AbstractActionController
{

    // Add this property:
    private $table;
    private $myrole;
    // Add this constructor:
    public function __construct(WechatTable $table,MyRole $myRole)
    {
        $this->user=MainlayoutController::checkLoginGetUserInfo();
        if(!$this->user->adminName){
            $this->redirect()->toRoute('auth');
        }
        $this->table = $table;
        $this->uid=$this->user->userid;
        $this->myrole = $myRole;
    }


    public function indexAction()
    {
        $obj = $this->myrole->isGranted('wechat.index.index');
        if(is_object($obj)){
            return $obj;
        }


        $where="uid=".$this->uid;
        $pageset=true;
        $paginator = $this->table->fetchAll($pageset,$where);
        if($pageset){
            $page = (int) $this->params()->fromQuery('page', 1);
            $page = ($page < 1) ? 1 : $page;
            $paginator->setCurrentPageNumber($page);
            //设置每页多少条
            $paginator->setItemCountPerPage(2);
        }
        //模板渲染
        return new ViewModel(['paginator' => $paginator]);
    }
    //添加
    public function addAction()
    {
        $form = new WechatForm();
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return ['form' => $form];
        }
        var_dump($request);
        exit;
        $wechat = new Wechat();
        $form->setInputFilter($wechat->getInputFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            return ['form' => $form];
        }
        $wechat->exchangeArray($form->getData());
        //var_dump($wechat);
        exit;
        $this->table->saveWechat($wechat);
        return $this->redirect()->toRoute('wechat');
    }
    //删除
    public function deleteAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('wechat');
        }
        $this->table->deleteWechat($id);
        return $this->redirect()->toRoute('wechat');
    }
    //上传
    public function uploadFormAction()
    {
        $form = new UploadForm('upload-form');
        $tempFile=null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                $file=$data['image-file']['tmp_name'];
                echo str_replace("\\",'/',$file);
                exit;
            }
        }
        return ['form' => $form];
    }
}