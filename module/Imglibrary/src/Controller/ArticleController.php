<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Imglibrary\Controller;

use Imglibrary\Model\ArticleTable;
use Imglibrary\Status\Work;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ArticleController extends AbstractActionController
{
    // Add this property:
    private $table;
    private $work;
    // Add this constructor:
    public function __construct(ArticleTable $table,Work $work)
    {
        $this->table = $table;
        $this->work = $work;
    }

    public function indexAction()
    {
        //问候语
        $this->work->hour = date("H");
        $call=$this->work->WriteCode();
        var_dump($call);die;
//        return new ViewModel([
//            'albums' => $this->table->fetchAll(),
//        ]);
    }
    //$this->getEvent()->getRouteMatch()->getParam('action', 'index');
    //$this->getEvent()->getRouteMatch()->getParam('controller', 'index');
    public function doSomethingCrazyAction()
    {
        // Get the "layout" view model and inject a sidebar
//        $layout = $this->layout();
//        $sidebarView = new ViewModel();
//        $sidebarView->setTemplate('content/sidebar');
//        $layout->addChild($sidebarView, 'sidebar');

        // Create and return a view model for the retrieved article
//        $view = new ViewModel(array('article' => $article));
//        $view->setTemplate('content/article');

        // Get the "layout" view model and set an alternate template
//        $layout = $this->layout();
//        $layout->setTemplate('article/layout');

//        $view = new ViewModel(array(
//            'message' => 'Hello world',
//        ));
//        $view->setTemplate('Album/Album/doSomething');
        // 关闭布局；`MvcEvent` 将使用这个视图模型实例
//        $view->setTerminal(true);
//        return $view;
    }

    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }

}
