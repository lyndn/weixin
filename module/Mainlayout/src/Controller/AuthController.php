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
    public function __construct(AuthInterface $auth,AuthTable $authTable,Adapter $adapter,ContainerInterface $serviceManager,MyRole $myRole,RoleTable $roleTable)
    {
        $this->auth = $auth;
        $this->authTable = $authTable;
        $this->adapter = $adapter;
        $this->serviceManager = $serviceManager->get('ServiceManager')->getServiceLocator();
        $this->myrole = $myRole;
        $this->wechatTable = $serviceManager->get(\Wechat\Model\WechatTable::class);
        $this->roleTable = $roleTable;
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
            "MD5(CONCAT('staticSalt', ?, password_salt))"
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
                'role' => $row->role
            ));
            return $this->redirect()->toRoute('admin', ['controller' => 'MainlayoutController',
                'action' => 'index']);
        }
    }

    /**
     * 角色管理列表
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
            $resultSet = $this->wechatTable->fetchAll();
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
                $view->setTemplate('Mainlayout/auth/addrole');
                return $view;
            }

            $data = $form->getData();
            $role->exchangeArray($data);
            $this->roleTable->saveRole($role);
            return $this->serviceManager->get('ViewHelperManager')
                ->get('inlineScript')->appendScript('alert("添加成功！");history.go(-1);');
        }

        $roleData = $this->roleTable->fetchAll();
        return new ViewModel(['roleData'=>$roleData]);
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
