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
    // Add this constructor:
    public function __construct(ContainerInterface $container,WorkList $workList,WorkAdd $workAdd)
    {
        $this->workList = $workList;
        $this->workAdd = $workAdd;
        $this->searchView = new ViewModel();
        $this->searchView->setTemplate('imglibrary/material/search');
        $this->container = $container;
    }


    /**
     * return json
     */
    public function indexAction()
    {
        $params = (string) $this->params()->fromRoute('do', 1);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $this->workList->type = $params;
        $this->workList->container = $this->container;
        $this->workList->page = $page;
        $this->workList->pageNum = 12;
        $call=$this->workList->WriteCode();
//        $json = Json::encode($call);
//        $view = new ViewModel(['json' => $json]);
        $view = new ViewModel($call);
        $view->addChild($this->searchView,'searchBar');
        return $view;
    }


    /**
     * return json
     */
    public function addAction()
    {
        $params = (string) $this->params()->fromRoute('do', 1);
        $this->workAdd->type = $params;
        $call=$this->workAdd->WriteCode();
        $json = Json::encode($call);
        echo $json;
        exit();
    }

}