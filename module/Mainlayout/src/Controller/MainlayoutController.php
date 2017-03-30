<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mainlayout\Controller;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Mainlayout\Model\MyRole;


class MainlayoutController extends AbstractActionController
{
    private $_tplPath;
    public $loginName;
    public $contentTemplatePath;
    public $contentFileName;
    public $myrole;
    /**
     * MainlayoutController constructor.
     */
    private $postRepository;
    private $auth;
    public $user;

    public function __construct(MyRole $myRole)
    {
        $this->user = $this->checkLoginGetUserInfo();
        $this->_tplPath = 'mainlayout/mainlayout';
        $this->myrole = $myRole;
    }


    /**
     * content macro use
     * @param bool $contentTemplatePath '{namespace name}/{module name}/action name}
     * @param bool $loginName
     * @param bool $contentFileName 'your_page'
     * @return ViewModel
     */
    public function contentMacro($contentTemplatePath = false, $loginName = false, $contentFileName = false)
    {
        return $this->_init();
    }

    /**
     * page macro use
     * @return ViewModel
     */
    public function pageMacro()
    {
        $page = new ViewModel();
        $page->setTemplate($this->_tplPath . '/pageNum');
        $page->setTerminal(true);
        return $page;
    }

    /**
     * init
     * @param bool $contentTemplatePath
     * @param bool $loginName
     * @param bool $contentFileName
     * @return ViewModel
     */
    private function _init()
    {

        $view = new ViewModel();

        // this is not needed since it matches "module/controller/action"
        $view->setTemplate('mainlayout/index/index');

        return $view;
    }

    /**
     * check user is on login
     * @return mixed|null
     */
    public function checkLoginGetUserInfo()
    {
        $user = null;
        $auth = new \Zend\Authentication\AuthenticationService();   //获取实例化Zend\AuthenticationService对象
        if ($auth->hasIdentity())    //判断是否登陆，是，则记录登陆信息
        {
            $user = $auth->getIdentity();
        }
        return $user;
    }

    /**
     * index
     */
    public function indexAction()
    {
        $obj = $this->myrole->isGranted('mainlayout.mainlayout.index');
        if(is_object($obj)){
            return $obj;
        }

        if(!isset($this->user->adminName)){
            return $this->redirect()->toRoute('auth');
        }
        return $this->_init();
    }

    /**
     * 刷新用户
     * @param $key
     * @param $value
     */
    private function refreshSession($value,$key='wechatID')
    {
        $wechat = new \Zend\Authentication\AuthenticationService();
        if ($wechat->hasIdentity()) {
            $user = $wechat->getIdentity();
            $s = [];
            foreach ($user as $k => $row) {
                $s[$k] = $row;
            }
            $s[$key] = $value;
            $wechat->getStorage()->write((object)$s);
        }
    }

    /**
     * 功能管理
     */
    public function addmenuAction()
    {
        $wechatid = (int) $this->params()->fromRoute('id', 0);
        if(!$wechatid)
        {
            exit("缺少参数:wechatid:$wechatid");
        }
        $this->refreshSession($wechatid);

        //....

        return $this->indexAction();

    }

    /**
     * home page
     */
    public function homeAction()
    {
        echo 'home';
        exit;
    }

}