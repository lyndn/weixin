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
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\Result;
use Mainlayout\Model\AuthInterface;
use Mainlayout\Form\LoginForm;
use Mainlayout\Model\Auth;
use Mainlayout\Model\AuthTable;

class AuthController extends AbstractActionController
{
    public $auth;
    public $authTable;
    public $adapter;
    public function __construct(AuthInterface $auth,AuthTable $authTable,Adapter $adapter)
    {
        $this->auth = $auth;
        $this->authTable = $authTable;
        $this->adapter = $adapter;
    }

    public function indexAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();
        if (! $request->isPost()) {
            $view = new ViewModel(['form' => $form]);
            $view->setTerminal(true);
            return $view;
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
        $username = $form->getValue('username');
        $password = $form->getValue('passwd');
        if (!$result->isValid()) {
            switch($result->getCode())
            {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    $errorMessage = "没有该用户";
                    break;
                case Result::FAILURE_CREDENTIAL_INVALID:
                    $errorMessage = "密码错误!";
                    break;
                default:
                    $errorMessage = "登录时发生错误!";
                    break;
            }
            return $this->redirect()->toRoute('auth',['controller' => 'AuthController',
                'action' => 'index']);
        } else {
            $auth = new \Zend\Authentication\AuthenticationService();
            $auth->getStorage()->write((object)array(
                'adminName' => $data['username'],
                'password' => $data['passwd'],
                'role' => '1'
            ));
            return $this->redirect()->toRoute('admin', ['controller' => 'MainlayoutController',
                'action' => 'index']);
        }
        return $this->redirect()->toRoute('admin');
    }
}