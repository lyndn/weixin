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

use Imglibrary\Status\Work;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MaterialController extends AbstractActionController
{
    // Add this property:
    private $work;

    // Add this constructor:
    public function __construct(Work $work)
    {
        $this->work = $work;
    }

    public function indexAction()
    {
        $params = (string) $this->params()->fromRoute('do', 1);
        $this->work->type = $params;
        $call=$this->work->WriteCode();
        var_dump($call);die;
    }

}