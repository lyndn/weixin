<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 14:37
 */
namespace Wechat\Controller;

use EasyWeChat\Foundation\Application;
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
        $this->user=MainlayoutController::checkLoginGetUserInfo();
        if(!$this->user->adminName){
            $this->redirect()->toRoute('auth');
        }
        $this->table=$table;
        $this->wxuser=$wxuser;
        $this->myrole=$myRole;
        $this->serviceManager=$serviceManager;
    }
    //公众号功能主页
    public function indexAction(){
        $obj = $this->myrole->isGranted('wechat.function.index');
        if(is_object($obj)){
            return $obj;
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        $view=new ViewModel(['id'=>$id]);
        $view->setTerminal(true);
        $view->setTemplate("wechat/function/index");
        return $view;
    }
    //菜单
    public function menuAction()
    {
        $obj = $this->myrole->isGranted('wechat.function.menu');
        if(is_object($obj)){
            return $obj;
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id){
            $form=new WxmenuForm();
            $mywx=$this->wxuser->getWechat($id);
            $menuRows=$this->table->getMenuRows($id);
            $temp=['mywx'=>$mywx,'menuRows'=>$menuRows,'form'=>$form];
            $view=new ViewModel($temp);
           // $view->setTerminal(true);
            //$view->setTemplate('wechat/function/menu');
            return $view;
        }else{
           return $this->redirect()->toRoute('wechat');
        }
    }
    //保存菜单
    public function savemenuAction()
    {
        $id=$this->params()->fromPost('id');
        if($id){
            $obj = $this->myrole->isGranted('wechat.function.editmenu');
        }else{
            $obj = $this->myrole->isGranted('wechat.function.savemenu');
        }
        if(is_object($obj)){ return $obj;}
        //以上为权限判断
        $request = $this->getRequest();
        $form=new WxmenuForm();
        $wxmenu=new Wxmenu();
        $form->setInputFilter($wxmenu->getInputFilter());
        $form->setData($request->getPost());
        //表单验证
        if (! $form->isValid()) {
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("表单验证失败！");history.go(-1);');
        }
        $data=$form->getData();
        $count=$this->table->countMenu($data['wxid'],$data['parentId']);
        if($id){
            //原栏目状态
            $c=$this->table->countMenu($data['wxid'],$id);
            $menu=$this->table->getMenuInfo($id);
            if($c && $menu->parentId!=$data['parentId']){
               return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("当前栏目下有子栏目，禁止操作");history.go(-1);');
            }
            $count--;
        }
        if($data['parentId']){
            if($count>=5){
                return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("二级栏目不得超过5个");history.go(-1);');
            }
        }else{
            if($count>=3){
                return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("一级栏目不得超过3个！");history.go(-1);');
            }
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
        $wxmenu->exchangeArray($data);
        $res=$this->table->saveMenu($wxmenu);
        if($res){
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("保存成功！");location.href="/function/menu/'.$data['wxid'].'";');
        }else{
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("保存失败！");history.go(-1);');
        }
    }

    //编辑菜单
    public function editmenuAction(){
        $obj = $this->myrole->isGranted('wechat.function.editmenu');
        if(is_object($obj)){
            return $obj;
        }
        $wxid = (int) $this->params()->fromRoute('id', 0);
        $menuid=(int)$_GET['menuid'];
        if($wxid && $menuid){
            $form=new WxmenuForm();
            $data=$this->table->getMenuInfo($menuid);
            $form->bind($data);

            $mywx=$this->wxuser->getWechat($wxid);
            $menuRows=$this->table->getMenuRows($wxid);
            $temp=['mywx'=>$mywx,'menuRows'=>$menuRows,'form'=>$form,'pid'=>$data->parentId];
            $view=new ViewModel($temp);
            //$view->setTerminal(true);
            $view->setTemplate('wechat/function/menu');
            return $view;
        }else{
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("参数出错");history.go(-1);');
        }
    }

    //删除菜单
    public function delmenuAction(){
        $obj = $this->myrole->isGranted('wechat.fucntion.delmenu');
        if(is_object($obj)){
            return $obj;
        }
        $wxid = (int) $this->params()->fromRoute('id', 0);
        $menuid=(int)$_GET['menuid'];
        if($wxid && $menuid){
            //需要增加安全判断
            $menu=$this->table->getMenuInfo($menuid);
            if($menu){
                if($wxid!=$menu->wxid){
                    return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("参数出错");history.go(-1);');
                }
                $count=$this->table->countMenu($menu->wxid,$menu->id);
                if($count){
                    return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("请先删除子栏目");history.go(-1);');
                }else{
                    if($this->table->deleteMenu($menuid)){
                        return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("删除成功");location.href="/function/menu/'.$menu->wxid.'";');
                    }else{
                        return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("删除失败");history.go(-1);');
                    }
                }
            }else{
                return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("参数出错");history.go(-1);');
            }
        }else{
            return $this->serviceManager->get('ViewHelperManager')->get('inlineScript')->appendScript('alert("参数出错");history.go(-1);');
        }
    }

    //上传到微信
    public function createAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        $button=$this->table->craeteMenu($id);
        if(count($button)) {
            $wxuser = $this->wxuser->getWechat($id);
            $config = [
                'debug' => true,
                'app_id' => $wxuser->appid,          // AppID
                'secret' => $wxuser->appsecret,      // AppSecret
                'token' => $wxuser->token,          // Token
                'aes_key' => $wxuser->AesEncodingKey, // EncodingAESKey，安全模式下请一定要填写！！！
                'log' => [
                    'level' => 'debug',
                    'permission' => 0777,
                    'file' => 'log/easywechat.log',
                ]
            ];
            $app = new Application($config);
            $menu = $app->menu;
            $res = $menu->add($button);
            echo json_encode(array('s' => $res->errcode, 'r' => $res->errmsg));
        }else{
            echo json_encode(array('s' => 1001, 'r' => '对不起，请您先添加菜单'));
        }
        exit;
    }
}