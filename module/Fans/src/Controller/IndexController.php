<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/28
 * Time: 16:46
 */
namespace Fans\Controller;
use EasyWeChat\Foundation\Application;
use Fans\Form\FansForm;
use Fans\Model\Fans;
use Fans\Model\FansTable;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Mainlayout\Controller\MainlayoutController;
use Mainlayout\Model\MyRole;
use Wechat\Model\WechatTable;
use Interop\Container\ContainerInterface;

class IndexController extends AbstractActionController{

    private $myrole;
    private $wechat;
    private $serviceManager;
    private $table;

    public function __construct(FansTable $table,MyRole $myRole,WechatTable $wechat,ContainerInterface $serviceManager)
    {
        $this->myrole=$myRole;
        $this->wechat=$wechat;
        $this->serviceManager=$serviceManager;
        $this->table=$table;
        $this->user=MainlayoutController::checkLoginGetUserInfo();
    }

    public function indexAction()
    {
        $id=(int) $this->params()->fromRoute('id',0);
        if($id){
            $wx=$this->wechat->getWechat($id);
            $where="1=1";
            $where.=" and app_id='$wx->appid'";
            $keyword=$_REQUEST['keyword'];
            if(!empty($keyword)){
                $where.=" and nickname like '%".$keyword."%'";
            }
            $pageset=true;
            $paginator = $this->table->listFans($pageset,$where);

            if($pageset){
                $page = (int) $this->params()->fromQuery('page', 1);
                $page = ($page < 1) ? 1 : $page;
                $paginator->setCurrentPageNumber($page);
                //设置每页多少条
                $paginator->setItemCountPerPage(1);
            }
            //模板渲染
            return new ViewModel(['paginator' => $paginator,'keyword'=>$keyword,'id'=>$id]);
        }else{
            echo '参数错误';
        }
    }

    public function groupAction()
    {
        $id=(int) $this->params()->fromRoute('id',0);
        if($id) {

        }else{
            echo '参数错误';
        }
        exit;
    }

    //获取信息
    public function getAction()
    {
        $id=(int) $this->params()->fromRoute('id',0);
        if($id){
            $user=$this->table->getFans($id);
            echo json_encode(array('s'=>1,'r'=>$user));
        }else{
            echo json_encode(array('s'=>0,'r'=>'参数错误'));
        }
        exit;
    }

    //更新数据
    public function updateAction(){
        $id=(int) $this->params()->fromRoute('id',0);
        if($id){
            $request=$this->getRequest();
            $form=new FansForm();
            $fans=new Fans();
            $form->setInputFilter($fans->getInputFilter());
            $form->setData($request->getPost());
            if(!$form->isValid()){
                echo json_encode(array('s'=>0,'r'=>'验证失败'));
            }else{
                $res=$form->getData();
                $data=[
                    'uid'=>$id,
                    'remark'=>$res['remark']
                ];
                if($this->table->updateFans($data)){
                    echo json_encode(array('s'=>1,'r'=>'修改成功'));
                }else{
                    echo json_encode(array('s'=>0,'r'=>'修改失败'));
                }
            }
        }else{
            echo json_encode(array('s'=>0,'r'=>'参数错误'));
        }
        exit;
    }

    //同步相关数据
    public function keepAction()
    {
        $id=(int) $this->params()->fromRoute('id',0);
        if($id) {
            $act=$this->getRequest()->getQuery('act');
            $config=$this->wechat->wxconfig($id);
            switch ($act){
                case 'fans':
                    $result=$this->userKeep($config);
                    break;
                case 'group':
                    $result = $this->groupKeep($config);
                    break;
            }
            var_dump($result);
           // echo json_encode(array('s'=>$result));
        }else{
            echo '参数错误';
        }
        exit;
    }

    //同步方法
    //同步微信粉丝
    public function userKeep($config)
    {
        $app = new Application($config);
        $userServer = $app->user;
        //获取列表（待完善）
        $list = $userServer->lists($nextOpenId = null);
        foreach ($list->data['openid'] as $rows) {
            $uid = $this->table->createUid(['app_id' => $config['app_id'], 'openid' => $rows]);
            $tabName = 'fans_' . $config['app_id'];
            $tabName .= '_' . substr($uid, -1);
            //还需要完善，判断表是否已经存在
            if (!$this->table->showTable($tabName)) {
                if ($this->table->createTable($tabName)) {
                    $this->table->unionTable();
                }
            }
            $user = $userServer->get($rows);
            $data = [
                'uid' => $uid,
                'app_id' => $config['app_id'],
                'subscribe' => $user->subscribe,
                'openid' => $user->openid,
                'nickname' => $user->nickname,
                'sex' => $user->sex,
                'language' => $user->language,
                'city' => $user->city,
                'province' => $user->province,
                'country' => $user->country,
                'headimgurl' => $user->headimgurl,
                'subscribe_time' => $user->subscribe_time,
                'remark' => $user->remark,
                'groupid' => $user->groupid,
                'tagid_list' => $user->tagid_list
            ];
            $this->table->insertFans($tabName, $data);
        }
        return true;
    }

    //同步粉丝组
    public function groupKeep($config){
        $app=new Application($config);
        $group=$app->user_group;
        $list=$group->lists();
        return $list;
    }

}