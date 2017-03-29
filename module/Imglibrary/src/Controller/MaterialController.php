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

class MaterialController extends AbstractActionController
{
    // Add this property:
    private $workList;
    private $workAdd;

    // Add this constructor:
    public function __construct(WorkList $workList,WorkAdd $workAdd)
    {
        $this->workList = $workList;
        $this->workAdd = $workAdd;
    }


    /**
     * return json
     */
    public function indexAction()
    {
        $params = (string) $this->params()->fromRoute('do', 1);
        $this->workList->type = $params;
        $call=$this->workList->WriteCode();
        $json = Json::encode($call);
        $view = new ViewModel(['json' => $json]);
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