<?php
/**
 *
 * PHP Version ～7.1
 * @package   AccountController.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/24 09:45
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Controller;

use Mainlayout\Form\AddUserForm;
use Mainlayout\Form\ResetPasswdForm;
use Mainlayout\Form\UpdateProfileForm;
use Mainlayout\Model\Auth;
use Mainlayout\Model\AuthTable;
use Mainlayout\Model\RoleTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Mainlayout\Model\MyRole;
use Zend\Math\Rand;
use Interop\Container\ContainerInterface;


class AccountController extends AbstractActionController
{
    private $myrole;
    private $roleTable;
    private $authTable;
    private $serviceManager;

    public function __construct(MyRole $myRole,RoleTable $roleTable,AuthTable $authTable,ContainerInterface $serviceManager)
    {
        $this->myrole = $myRole;
        $this->roleTable = $roleTable;
        $this->authTable = $authTable;
        $this->serviceManager = $serviceManager;
    }

    /**
     * 账户管理
     * @return bool|ViewModel
     */
    public function indexAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.account.index');
        if(is_object($obj)){
            return $obj;
        }

        $resutlSet = $this->authTable->fetchAll(true);

        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $resutlSet->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $resutlSet->setItemCountPerPage(10);

        $roleResultSet = $this->roleTable->fetchAll();
        $role = [];
        foreach ($roleResultSet as $row)
        {
            $role[$row->roleid] = $row->roletitle;
        }
        $view = new ViewModel(['resultSet'=>$resutlSet,'roleResultSet'=>$role]);
        return $view;
    }


    /**
     * 账户新增
     */
    public function adduserAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.account.adduser');
        if(is_object($obj)){
            return $obj;
        }

        $resultSet = $this->roleTable->fetchAll();
        $result = [];
        foreach ($resultSet as $row)
        {
            $result[] = $row;
        }
        $form = new AddUserForm($result);
        $request = $this->getRequest();
        if (! $request->isPost()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }

        $auth = new Auth();
        $form->setInputFilter($auth->getInputAddUserFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }

        $data = $form->getData();

        $salt = base64_encode(Rand::getBytes(32, true));

        $data['password'] = MD5('staticSalt'.$data['password'].$salt);
        $data = [
            'realname'=>$data['realname'],
            'username'=>$data['username'],
            'passwd'=>$data['password'],
            'password_salt'=>$salt,
            'createdate' => date('Y-m-d H:i:s'),
            'role' => $data['role']
        ];

        $auth->exchangeArray($data);
        $this->authTable->saveAuth($auth);

        return $this->serviceManager->get('ViewHelperManager')
            ->get('inlineScript')->appendScript('alert("添加成功！");history.go(-1);');

    }

    /**
     * 重置密码
     */
    public function resetpasswdAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.account.resetpasswd');
        if(is_object($obj)){
            return $obj;
        }

        $form = new ResetPasswdForm();
        $request = $this->getRequest();
        if (! $request->isPost()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }

        $auth = new Auth();
        $form->setInputFilter($auth->getInputResetPasswdFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }

        $data = $form->getData();

        $salt = base64_encode(Rand::getBytes(32, true));

        $data['password'] = MD5('staticSalt'.$data['password'].$salt);

        $data = [
            'id' => $this->getRequest()->getQuery('id'),
            'passwd'=>$data['password'],
            'password_salt'=>$salt,
        ];

        $auth->exchangeArray($data);
        $this->authTable->saveAuth($auth);

        return $this->serviceManager->get('ViewHelperManager')
            ->get('inlineScript')->appendScript('alert("修改密码成功！");history.go(-2);');


    }

    /**
     * 修改信息
     */

    public function updateprofileAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.account.updateprofile');
        if(is_object($obj)){
            return $obj;
        }

        $resultSet = $this->roleTable->fetchAll();
        $result = [];
        foreach ($resultSet as $row)
        {
            $result[] = $row;
        }

        $form = new UpdateProfileForm($result);
        $request = $this->getRequest();
        if (! $request->isPost()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }

        $auth = new Auth();
        $form->setInputFilter($auth->getInputUpdateFilter());
        $form->setData($request->getPost());
        if (! $form->isValid()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }

        $data = $form->getData();

        $data = [
            'id' =>  $this->getRequest()->getQuery('id'),
            'realname'=>$data['realname'],
            'username'=>$data['username'],
            'role' => $data['role']
        ];

        $auth->exchangeArray($data);
        $this->authTable->saveAuth($auth);
        return $this->serviceManager->get('ViewHelperManager')
            ->get('inlineScript')->appendScript('alert("修改成功！");history.go(-1);');

    }

    /**
     * 删除用户
     */
    public function deluserAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.account.deluser');
        if(is_object($obj)){
            return $obj;
        }

        $auth = new Auth();
        $data = ['id'=>$this->getRequest()->getQuery('id'),'active'=>1];
        $auth->exchangeArray($data);
        $this->authTable->saveAuth($auth);
        return $this->serviceManager->get('ViewHelperManager')
            ->get('inlineScript')->appendScript('alert("禁用成功！");history.go(-1);');
    }



}