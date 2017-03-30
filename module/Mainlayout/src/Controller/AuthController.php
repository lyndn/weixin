<?php
/**
 *
 * PHP Version ～7.1
 * @package   AuthController.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 21:52
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Controller;

use Mainlayout\Form\SetPermissionForm;
use Mainlayout\Model\Modules;
use Mainlayout\Model\ModulesTable;
use Mainlayout\Model\MyRole;
use Mainlayout\Model\Role;
use Mainlayout\Model\RoleTable;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\Result;
use Mainlayout\Model\AuthInterface;
use Mainlayout\Form\LoginForm;
use Mainlayout\Form\AddRoleForm;
use Mainlayout\Model\Auth;
use Mainlayout\Model\AuthTable;
use Interop\Container\ContainerInterface;
use Zend\Debug\Debug;

class AuthController extends AbstractActionController
{
    public $auth;
    public $authTable;
    public $adapter;
    public $serviceManager;
    public $myrole;
    public $wechatTable;
    public $roleTable;
    public $moduleTable;
    private $user;
    public function __construct(AuthInterface $auth,AuthTable $authTable,Adapter $adapter,ContainerInterface $serviceManager,MyRole $myRole,RoleTable $roleTable,ModulesTable $modulesTable)
    {
        $this->auth = $auth;
        $this->authTable = $authTable;
        $this->adapter = $adapter;
        $this->serviceManager = $serviceManager->get('ServiceManager')->getServiceLocator();
        $this->myrole = $myRole;
        $this->wechatTable = $serviceManager->get(\Wechat\Model\WechatTable::class);
        $this->roleTable = $roleTable;
        $this->moduleTable = $modulesTable;
        $auth = new \Zend\Authentication\AuthenticationService();
        if ($auth->hasIdentity())
        {
            $this->user = $auth->getIdentity();
        }
    }

    public function indexAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->auth->viewLoginForm($form);
        }

        $auth = new Auth();
        $form->setInputFilter($auth->getInputFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            return ['form' => $form];
        }
        $data = $form->getData();
        $authAdapter = new AuthAdapter(
            $this->adapter,
            'users',
            'username',
            'passwd',
            "MD5(CONCAT('staticSalt', ?, password_salt)) AND active = '0'"
        );
        $authAdapter
            ->setIdentity($data['username'])
            ->setCredential($data['passwd']);
        $result = $authAdapter->authenticate();
        if (!$result->isValid()) {
            switch($result->getCode())
            {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    /** do stuff for nonexistent identity **/
                    $js = 'alert("没有这个用户！");history.go(-1);';
                    return $this->serviceManager->get('ViewHelperManager')
                        ->get('inlineScript')->appendScript($js);
                    break;

                case Result::FAILURE_CREDENTIAL_INVALID:
                    /** do stuff for invalid credential **/
                    $js = 'alert("密码错误");history.go(-1);';
                    return $this->serviceManager->get('ViewHelperManager')
                        ->get('inlineScript')->appendScript($js);
                    break;
                default:
                    /** do stuff for other failure **/
                    break;
            }
        } else {
            $auth = new \Zend\Authentication\AuthenticationService();
            $row = $this->authTable->getAuth($data['username']);

            $auth->getStorage()->write((object)array(
                'userid' => $row->id,
                'adminName' => $data['username'],
                'password' => $data['passwd'],
                'role' => $row->role,
                'pid' => $row->pid
            ));
            return $this->redirect()->toRoute('admin', ['controller' => 'MainlayoutController',
                'action' => 'index']);
        }
    }

    /**
     * 角色管理-099 -
     * @return bool
     */
    public function roleAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.auth.role');
        if(is_object($obj)){
            return $obj;
        }

        $view = null;
        $do = $this->getRequest()->getQuery('do');

        //添加角色
        if($do == 'addrole'){
            $obj = $this->myrole->isGranted('mainlayout.auth.addrole');
            if(is_object($obj)){
                return $obj;
            }
            $selectCnt =  [];
            $resultSet = $this->wechatTable->fetchAll(false,['operId'=>$this->user->userid]);
            foreach ($resultSet as $row){
                $selectCnt[$row->id] = $row->wxname;
            }
            $form = new AddRoleForm($selectCnt);
            $request = $this->getRequest();
            if (! $request->isPost()) {
                return $this->auth->viewAddRoleForm($form);
            }

            $role = new Role();
            $form->setInputFilter($role->getInputFilter());
            $form->setData($request->getPost());
            if (! $form->isValid()) {
                $view = new ViewModel(['form' => $form]);
                $view->setTemplate('mainlayout/auth/addrole');
                return $view;
            }

            $data = $form->getData();
            $data['rpid'] = $this->user->userid;
            $role->exchangeArray($data);
            $this->roleTable->saveRole($role);
            return $this->serviceManager->get('ViewHelperManager')
                ->get('inlineScript')->appendScript('alert("添加成功！");history.go(-1);');
        }
        $where = $this->user->userid == 1 ? null : ['rpid'=>$this->user->userid];
        $roleData = $this->roleTable->fetchAll(true,$where);

        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $roleData->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $roleData->setItemCountPerPage(10);

        return new ViewModel(['roleData'=>$roleData]);
    }

    /**
     * 权限设置
     */
    public function settingAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.auth.setting','Mainlayout\Model\RoleTable','rpid','roleid',$this->getRequest()->getQuery('id'));
        if(is_object($obj)){
            return $obj;
        }

        $view = null;

        $moduleResultSet = $this->moduleTable->fetchAll();
        $mResult = [];
        foreach ($moduleResultSet as $row)
        {
            $mResult[] = $row;
        }


        $roleid = $this->getRequest()->getQuery('id');
        if(empty($roleid))
        {
            exit('缺少roleid参数');
        }

        $userPermissionResultSet = $this->myrole->getGroupPermission($roleid);
        $form = new SetPermissionForm($mResult,$userPermissionResultSet);
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->auth->viewSetPermissionForm($form,$mResult,$roleid);
        }


        $getData = $request->getPost();
        $data = [];
        foreach ($getData as $key=>$value)
        {
            if($value != '设置'){
                $data[] = $key;
            }
        }

        $this->myrole->deleteGroupRole(['groupid'=>$roleid]);
        $this->myrole->setGroupRole(['groupid'=>$roleid,'powerid'=>$data]);
        return $this->serviceManager->get('ViewHelperManager')
            ->get('inlineScript')->appendScript('alert("设置成功！");history.go(-1);');

    }

    /*
     * 删除角色
     */

    public function delroleAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.auth.delrole','Mainlayout\Model\RoleTable','rpid','roleid',$this->getRequest()->getQuery('id'));
        if(is_object($obj)){
            return $obj;
        }

        $roleid = $this->getRequest()->getQuery('id');

        $row = $this->authTable->getAuthWhere(['role'=>$roleid]);
        if(!empty($row)){
            return $this->serviceManager->get('ViewHelperManager')
                ->get('inlineScript')->appendScript('alert("删除失败，该角色下人仍又用户存在！");history.go(-1);');
        }
        $this->myrole->deleteGroupPermission($roleid);
        $this->roleTable->deleteRole($roleid);
        return $this->serviceManager->get('ViewHelperManager')
            ->get('inlineScript')->appendScript('alert("删除成功！");history.go(-1);');
    }

    /**
     * 退出登录
     * clear session user
     */
    public function logoutAction()
    {
        $user = null;
        $auth = new \Zend\Authentication\AuthenticationService();
        $auth->clearIdentity();
        return $this->redirect()->toRoute('auth',['controller' => 'AuthController',
            'action' => 'index']);
    }





    
}
