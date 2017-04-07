<?php
/**
 *
 * PHP Version ～7.1
 * @package   materialController.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/29 10:12
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Imglibrary\Controller;

use Imglibrary\Status\WorkAdd;
use Imglibrary\Status\WorkList;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use Zend\View\Model\ViewModel;
use Interop\Container\ContainerInterface;

class MaterialController extends AbstractActionController
{
    // Add this property:
    private $workList;
    private $workAdd;
    private $searchView;
    private $container;
    private $user;
    // Add this constructor:
    public function __construct(ContainerInterface $container,WorkList $workList,WorkAdd $workAdd)
    {
        $this->workList = $workList;
        $this->workAdd = $workAdd;
        $this->searchView = new ViewModel();
        $this->searchView->setTemplate('imglibrary/material/search');
        $this->container = $container;
        $user = new \Zend\Authentication\AuthenticationService();
        if ($user->hasIdentity())
        {
            $this->user = $user->getIdentity();
        }
    }


    /**
     * return json
     */
    public function indexAction()
    {
        if(empty($this->user->adminName)){
            return $this->redirect()->toRoute('auth',['controller' => 'AuthController',
                'action' => 'index']);
        }
        $params = (string) $this->params()->fromRoute('do', 1);
        $params_ac = (string) $this->params()->fromRoute('ac',2);
        $page = (int) $this->params()->fromQuery('page', 1);
        $pageNum = (int) $this->params()->fromQuery('pageNum', 12);
        $page = ($page < 1) ? 1 : $page;
        $keyword_py = (string) $this->params()->fromQuery('keyword_py');
        $beginTime = (string) $this->params()->fromQuery('beginTime');
        $endTime = (string) $this->params()->fromQuery('endTime');
        $materialStatus = (string) $this->params()->fromQuery('materialStatus');
        $this->workList->type = $params;
        $this->workList->ac = $params_ac;
        $this->workList->keyword_py = $keyword_py;
        $this->workList->container = $this->container;
        $this->workList->page = $page;
        $this->workList->pageNum = $pageNum;
        $this->workList->beginTime = $beginTime;
        $this->workList->endTime = $endTime;
        $this->workList->materialStatus = $materialStatus;
        $this->workList->user = $this->user;
        $call=$this->workList->WriteCode();
        $view = new ViewModel($call);
        $view->addChild($this->searchView,'searchBar');
        return $view;
    }


    /**
     * return json
     */
    public function addAction()
    {
        if(empty($this->user->adminName)){
            return $this->redirect()->toRoute('auth',['controller' => 'AuthController',
                'action' => 'index']);
        }
        $params = (string) $this->params()->fromRoute('do', 1);
        $params_ac = (string) $this->params()->fromRoute('ac',2);
        $request = $this->getRequest();
        $this->workAdd->type = $params;
        $this->workAdd->ac = $params_ac;
        $this->workAdd->request = $request;
        $this->workAdd->user = $this->user;
        $this->workAdd->container = $this->container;
        $this->workAdd->view = new ViewModel();
        $call=$this->workAdd->WriteCode();
        return $this->workAdd->view;
    }



}